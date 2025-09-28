import './bootstrap';
import { only_digits } from "./functions.js";

window.only_digits = only_digits;

document.addEventListener('alpine:init', () => {
    Alpine.data('timer', (minutes, storageKey = 'resend_timer_start') => ({
        totalSeconds: minutes * 60,
        init() {
            this.restoreFromStorage();
            if (this.totalSeconds > 0) {
                this.startCountdown();
            }
        },
        restoreFromStorage() {
            const saved = localStorage.getItem(storageKey);
            const now = Math.floor(Date.now() / 1000);

            if (saved) {
                const elapsed = now - parseInt(saved);
                const remaining = (minutes * 60) - elapsed;
                this.totalSeconds = remaining > 0 ? remaining : 0;
            } else {
                this.totalSeconds = 0;
            }
        },
        startCountdown() {
            if (this.interval) clearInterval(this.interval);

            this.interval = setInterval(() => {
                if (this.totalSeconds > 0) {
                    this.totalSeconds--;
                } else {
                    clearInterval(this.interval);
                    localStorage.removeItem(storageKey);
                    this.$dispatch('resend-allowed');
                }
            }, 1000);
        },
        restart() {
            this.totalSeconds = minutes * 60;
            localStorage.setItem(storageKey, Math.floor(Date.now() / 1000).toString());
            this.startCountdown();
        },
        formatTime() {
            const minutes = Math.floor(this.totalSeconds / 60);
            const seconds = this.totalSeconds % 60;
            return `${minutes}:${seconds.toString().padStart(2, '0')}`;
        }

    }));
    Alpine.data('handleTheme', (theme) => ({
        isThemeSystem: theme === 'system',
        setTheme(e) {
            this.$flux
            this.isThemeSystem = e.target.checked;
            if (e.target.checked) {
                this.$flux.appearance = 'system';
            } else {
                this.$flux.appearance = 'dark';
            }
        }
    }));
});
