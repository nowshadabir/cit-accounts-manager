/**
 * CIT Accounts - Progressive Web App (PWA) Manager
 * Handles Service Worker registration, install prompts, offline alerts, and update flows.
 */

(() => {
    let deferredInstallPrompt = null;
    let newWorker = null;

    const isStandalone = () => {
        return window.matchMedia('(display-mode: standalone)').matches ||
               window.navigator.standalone === true ||
               document.referrer.includes('android-app://');
    };

    const isIos = () => {
        const userAgent = window.navigator.userAgent.toLowerCase();
        return /iphone|ipad|ipod/.test(userAgent);
    };

    // 1. Toast Notification Utility
    const showToast = (message, type = 'info', actionBtn = null) => {
        let toastContainer = document.getElementById('pwaToastContainer');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'pwaToastContainer';
            toastContainer.className = 'pwa-toast-container';
            document.body.appendChild(toastContainer);
        }

        const toast = document.createElement('div');
        toast.className = `pwa-toast pwa-toast-${type}`;

        let iconSvg = '';
        if (type === 'offline') {
            iconSvg = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="1" y1="1" x2="23" y2="23"/><path d="M16.72 11.06A10.94 10.94 0 0 1 19 12.55"/><path d="M5 12.55a10.94 10.94 0 0 1 5.17-2.39"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>`;
        } else if (type === 'online') {
            iconSvg = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>`;
        } else if (type === 'update') {
            iconSvg = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>`;
        } else {
            iconSvg = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>`;
        }

        toast.innerHTML = `
            <div class="pwa-toast-content">
                <span class="pwa-toast-icon">${iconSvg}</span>
                <span class="pwa-toast-message">${message}</span>
            </div>
        `;

        if (actionBtn) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'pwa-toast-action-btn';
            btn.textContent = actionBtn.text;
            btn.onclick = () => {
                actionBtn.onClick();
                toast.remove();
            };
            toast.appendChild(btn);
        }

        const closeBtn = document.createElement('button');
        closeBtn.type = 'button';
        closeBtn.className = 'pwa-toast-close-btn';
        closeBtn.innerHTML = '&times;';
        closeBtn.onclick = () => toast.remove();
        toast.appendChild(closeBtn);

        toastContainer.appendChild(toast);

        if (type === 'online' || (type === 'info' && !actionBtn)) {
            setTimeout(() => {
                toast.classList.add('fade-out');
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        }

        return toast;
    };

    // 2. Service Worker Registration & Update Lifecycle
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js', { scope: '/' })
                .then((registration) => {
                    console.log('[PWA] Service Worker registered with scope:', registration.scope);

                    // Check for updates
                    registration.addEventListener('updatefound', () => {
                        newWorker = registration.installing;
                        if (newWorker) {
                            newWorker.addEventListener('statechange', () => {
                                if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                    showToast('A new update is available for CIT Accounts.', 'update', {
                                        text: 'Refresh Now',
                                        onClick: () => {
                                            newWorker.postMessage({ action: 'skipWaiting' });
                                            window.location.reload();
                                        }
                                    });
                                }
                            });
                        }
                    });
                })
                .catch((error) => {
                    console.warn('[PWA] Service Worker registration failed:', error);
                });

            // Reload page on controller change
            let refreshing = false;
            navigator.serviceWorker.addEventListener('controllerchange', () => {
                if (!refreshing) {
                    refreshing = true;
                    window.location.reload();
                }
            });
        });
    }

    // 3. Network Online / Offline Detection
    let activeOfflineToast = null;
    window.addEventListener('offline', () => {
        if (!activeOfflineToast) {
            activeOfflineToast = showToast('You are currently offline. Showing cached financial data.', 'offline');
        }
    });

    window.addEventListener('online', () => {
        if (activeOfflineToast) {
            activeOfflineToast.remove();
            activeOfflineToast = null;
        }
        showToast('Internet connection restored!', 'online');
    });

    // 4. Before Install Prompt Handler (Chrome / Edge / Android)
    window.addEventListener('beforeinstallprompt', (e) => {
        // Prevent default mini-infobar
        e.preventDefault();
        deferredInstallPrompt = e;
        console.log('[PWA] beforeinstallprompt captured.');

        // Show any install buttons in the UI
        const installBtns = document.querySelectorAll('.pwa-install-trigger');
        installBtns.forEach(btn => {
            btn.style.display = 'inline-flex';
        });

        // Show floating / banner install prompt if not standalone and not recently dismissed
        if (!isStandalone() && !localStorage.getItem('cit_pwa_banner_dismissed')) {
            showInstallBanner();
        }
    });

    window.addEventListener('appinstalled', () => {
        console.log('[PWA] App successfully installed.');
        deferredInstallPrompt = null;
        hideInstallBanner();
        const installBtns = document.querySelectorAll('.pwa-install-trigger');
        installBtns.forEach(btn => {
            btn.style.display = 'none';
        });
        showToast('CIT Accounts installed successfully!', 'online');
    });

    // 5. In-App Install Banner / Drawer Function
    const showInstallBanner = () => {
        if (document.getElementById('pwaInstallBanner') || isStandalone()) return;

        const banner = document.createElement('div');
        banner.id = 'pwaInstallBanner';
        banner.className = 'pwa-install-banner';
        banner.innerHTML = `
            <div class="pwa-install-banner-inner">
                <img src="/icons/icon-192x192.png" alt="App Icon" class="pwa-install-icon" />
                <div class="pwa-install-text">
                    <strong>Install CIT Accounts</strong>
                    <span>Faster access, full-screen mobile experience & offline support.</span>
                </div>
                <div class="pwa-install-actions">
                    <button type="button" class="pwa-btn-primary" id="pwaBannerInstallBtn">Install</button>
                    <button type="button" class="pwa-btn-dismiss" id="pwaBannerDismissBtn" aria-label="Dismiss">&times;</button>
                </div>
            </div>
        `;

        document.body.appendChild(banner);

        document.getElementById('pwaBannerInstallBtn')?.addEventListener('click', () => {
            triggerPwaInstall();
        });

        document.getElementById('pwaBannerDismissBtn')?.addEventListener('click', () => {
            hideInstallBanner();
            localStorage.setItem('cit_pwa_banner_dismissed', 'true');
        });
    };

    const hideInstallBanner = () => {
        const banner = document.getElementById('pwaInstallBanner');
        if (banner) {
            banner.classList.add('pwa-slide-down');
            setTimeout(() => banner.remove(), 250);
        }
    };

    const showIosInstallModal = () => {
        if (document.getElementById('pwaIosModal')) return;

        const modal = document.createElement('div');
        modal.id = 'pwaIosModal';
        modal.className = 'pwa-ios-modal-overlay';
        modal.innerHTML = `
            <div class="pwa-ios-modal">
                <div class="pwa-ios-header">
                    <img src="/icons/apple-touch-icon.png" width="48" height="48" style="border-radius: 10px;" alt="CIT Accounts" />
                    <div>
                        <h3 style="margin: 0; font-size: 1.1rem; font-weight: 700;">Install on iOS</h3>
                        <p style="margin: 0; font-size: 0.8rem; color: #64748b;">Add to your iPhone or iPad Home Screen</p>
                    </div>
                </div>
                <div class="pwa-ios-steps">
                    <div class="pwa-step">
                        <span class="step-num">1</span>
                        <span>Tap the <strong>Share</strong> icon <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle;"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg> at the bottom of Safari.</span>
                    </div>
                    <div class="pwa-step">
                        <span class="step-num">2</span>
                        <span>Scroll down and tap <strong>Add to Home Screen</strong> <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle;"><rect width="18" height="18" x="3" y="3" rx="2"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>.</span>
                    </div>
                    <div class="pwa-step">
                        <span class="step-num">3</span>
                        <span>Tap <strong>Add</strong> in the top right corner.</span>
                    </div>
                </div>
                <button type="button" class="pwa-ios-close-btn" id="pwaIosCloseBtn">Got It</button>
            </div>
        `;

        document.body.appendChild(modal);

        document.getElementById('pwaIosCloseBtn')?.addEventListener('click', () => {
            modal.remove();
        });
        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.remove();
        });
    };

    const triggerPwaInstall = async () => {
        if (deferredInstallPrompt) {
            deferredInstallPrompt.prompt();
            const choiceResult = await deferredInstallPrompt.userChoice;
            console.log('[PWA] User response to install:', choiceResult.outcome);
            deferredInstallPrompt = null;
            hideInstallBanner();
        } else if (isIos()) {
            showIosInstallModal();
        } else {
            showToast('To install, open browser menu (⋮) and choose "Install App" or "Add to Home screen".', 'info');
        }
    };

    // Attach click listeners to any .pwa-install-trigger buttons
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.pwa-install-trigger').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                triggerPwaInstall();
            });
        });
    });

    // Expose to window for manual calls
    window.PWA = {
        install: triggerPwaInstall,
        isStandalone: isStandalone(),
        isIos: isIos(),
        showToast: showToast
    };
})();
