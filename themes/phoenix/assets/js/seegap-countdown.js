/**
 * SeeGap Custom Countdown Engine
 * A lightweight, dependency-free countdown timer with multiple styles
 * Copyright (c) 2025 SeeGap Ltd.
 */

class SeeGapCountdown {
    constructor(options) {
        this.containerId = options.containerId;
        this.endDate = new Date(options.endDate * 1000); // Convert from timestamp
        this.style = options.style || 'digital-led';
        this.theme = options.theme || 'light';
        this.labels = options.labels || ['Days', 'Hours', 'Minutes', 'Seconds'];
        this.onComplete = options.onComplete || (() => {});
        
        this.container = document.getElementById(this.containerId);
        this.interval = null;
        this.isActive = true;
        
        this.init();
    }
    
    init() {
        if (!this.container) {
            console.error('SeeGapCountdown: Container not found');
            return;
        }
        
        this.container.className = `seegap-countdown ${this.style} ${this.theme}`;
        this.render();
        this.start();
    }
    
    start() {
        this.update();
        this.interval = setInterval(() => {
            this.update();
        }, 1000);
    }
    
    stop() {
        if (this.interval) {
            clearInterval(this.interval);
            this.interval = null;
        }
        this.isActive = false;
    }
    
    update() {
        const now = new Date().getTime();
        const distance = this.endDate.getTime() - now;
        
        if (distance < 0) {
            this.stop();
            this.onComplete();
            this.renderCompleted();
            return;
        }
        
        const timeLeft = this.calculateTimeLeft(distance);
        this.updateDisplay(timeLeft);
    }
    
    calculateTimeLeft(distance) {
        return {
            days: Math.floor(distance / (1000 * 60 * 60 * 24)),
            hours: Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
            minutes: Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)),
            seconds: Math.floor((distance % (1000 * 60)) / 1000)
        };
    }
    
    render() {
        switch (this.style) {
            case 'digital-led':
                this.renderDigitalLED();
                break;
            case 'digital-lcd':
                this.renderDigitalLCD();
                break;
            case 'neon-style':
                this.renderNeonStyle();
                break;
            case 'matrix-style':
                this.renderMatrixStyle();
                break;
            case 'circular-progress':
                this.renderCircularProgress();
                break;
            case 'gauge-style':
                this.renderGaugeStyle();
                break;
            case 'card-flip':
                this.renderCardFlip();
                break;
            case 'slide-animation':
                this.renderSlideAnimation();
                break;
            case 'glassmorphism':
                this.renderGlassmorphism();
                break;
            case 'neumorphism':
                this.renderNeumorphism();
                break;
            case 'gradient':
                this.renderGradient();
                break;
            case 'minimalist':
                this.renderMinimalist();
                break;
            default:
                this.renderDigitalLED();
        }
    }
    
    renderDigitalLED() {
        this.container.innerHTML = `
            <div class="countdown-display">
                <div class="time-unit">
                    <div class="time-value" data-unit="days">00</div>
                    <div class="time-label">${this.labels[0]}</div>
                </div>
                <div class="separator">:</div>
                <div class="time-unit">
                    <div class="time-value" data-unit="hours">00</div>
                    <div class="time-label">${this.labels[1]}</div>
                </div>
                <div class="separator">:</div>
                <div class="time-unit">
                    <div class="time-value" data-unit="minutes">00</div>
                    <div class="time-label">${this.labels[2]}</div>
                </div>
                <div class="separator">:</div>
                <div class="time-unit">
                    <div class="time-value" data-unit="seconds">00</div>
                    <div class="time-label">${this.labels[3]}</div>
                </div>
            </div>
        `;
    }
    
    renderDigitalLCD() {
        this.container.innerHTML = `
            <div class="countdown-display lcd-display">
                <div class="lcd-screen">
                    <div class="time-segment">
                        <span class="time-value" data-unit="days">00</span>
                        <span class="time-label">${this.labels[0]}</span>
                    </div>
                    <div class="time-segment">
                        <span class="time-value" data-unit="hours">00</span>
                        <span class="time-label">${this.labels[1]}</span>
                    </div>
                    <div class="time-segment">
                        <span class="time-value" data-unit="minutes">00</span>
                        <span class="time-label">${this.labels[2]}</span>
                    </div>
                    <div class="time-segment">
                        <span class="time-value" data-unit="seconds">00</span>
                        <span class="time-label">${this.labels[3]}</span>
                    </div>
                </div>
            </div>
        `;
    }
    
    renderNeonStyle() {
        this.container.innerHTML = `
            <div class="countdown-display neon-display">
                <div class="neon-container">
                    <div class="neon-unit">
                        <div class="neon-value" data-unit="days">00</div>
                        <div class="neon-label">${this.labels[0]}</div>
                    </div>
                    <div class="neon-separator">:</div>
                    <div class="neon-unit">
                        <div class="neon-value" data-unit="hours">00</div>
                        <div class="neon-label">${this.labels[1]}</div>
                    </div>
                    <div class="neon-separator">:</div>
                    <div class="neon-unit">
                        <div class="neon-value" data-unit="minutes">00</div>
                        <div class="neon-label">${this.labels[2]}</div>
                    </div>
                    <div class="neon-separator">:</div>
                    <div class="neon-unit">
                        <div class="neon-value" data-unit="seconds">00</div>
                        <div class="neon-label">${this.labels[3]}</div>
                    </div>
                </div>
            </div>
        `;
    }
    
    renderMatrixStyle() {
        this.container.innerHTML = `
            <div class="countdown-display matrix-display">
                <div class="matrix-background"></div>
                <div class="matrix-content">
                    <div class="matrix-unit">
                        <div class="matrix-value" data-unit="days">00</div>
                        <div class="matrix-label">${this.labels[0]}</div>
                    </div>
                    <div class="matrix-unit">
                        <div class="matrix-value" data-unit="hours">00</div>
                        <div class="matrix-label">${this.labels[1]}</div>
                    </div>
                    <div class="matrix-unit">
                        <div class="matrix-value" data-unit="minutes">00</div>
                        <div class="matrix-label">${this.labels[2]}</div>
                    </div>
                    <div class="matrix-unit">
                        <div class="matrix-value" data-unit="seconds">00</div>
                        <div class="matrix-label">${this.labels[3]}</div>
                    </div>
                </div>
            </div>
        `;
    }
    
    renderCircularProgress() {
        this.container.innerHTML = `
            <div class="countdown-display circular-display">
                <div class="circular-container">
                    <div class="circular-unit">
                        <svg class="progress-ring" width="120" height="120">
                            <circle class="progress-ring-bg" cx="60" cy="60" r="50"></circle>
                            <circle class="progress-ring-fill" cx="60" cy="60" r="50" data-unit="days"></circle>
                        </svg>
                        <div class="circular-content">
                            <div class="circular-value" data-unit="days">00</div>
                            <div class="circular-label">${this.labels[0]}</div>
                        </div>
                    </div>
                    <div class="circular-unit">
                        <svg class="progress-ring" width="120" height="120">
                            <circle class="progress-ring-bg" cx="60" cy="60" r="50"></circle>
                            <circle class="progress-ring-fill" cx="60" cy="60" r="50" data-unit="hours"></circle>
                        </svg>
                        <div class="circular-content">
                            <div class="circular-value" data-unit="hours">00</div>
                            <div class="circular-label">${this.labels[1]}</div>
                        </div>
                    </div>
                    <div class="circular-unit">
                        <svg class="progress-ring" width="120" height="120">
                            <circle class="progress-ring-bg" cx="60" cy="60" r="50"></circle>
                            <circle class="progress-ring-fill" cx="60" cy="60" r="50" data-unit="minutes"></circle>
                        </svg>
                        <div class="circular-content">
                            <div class="circular-value" data-unit="minutes">00</div>
                            <div class="circular-label">${this.labels[2]}</div>
                        </div>
                    </div>
                    <div class="circular-unit">
                        <svg class="progress-ring" width="120" height="120">
                            <circle class="progress-ring-bg" cx="60" cy="60" r="50"></circle>
                            <circle class="progress-ring-fill" cx="60" cy="60" r="50" data-unit="seconds"></circle>
                        </svg>
                        <div class="circular-content">
                            <div class="circular-value" data-unit="seconds">00</div>
                            <div class="circular-label">${this.labels[3]}</div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    renderGaugeStyle() {
        this.container.innerHTML = `
            <div class="countdown-display gauge-display">
                <div class="gauge-container">
                    <div class="gauge-unit">
                        <div class="gauge-meter">
                            <div class="gauge-fill" data-unit="days"></div>
                            <div class="gauge-center">
                                <div class="gauge-value" data-unit="days">00</div>
                                <div class="gauge-label">${this.labels[0]}</div>
                            </div>
                        </div>
                    </div>
                    <div class="gauge-unit">
                        <div class="gauge-meter">
                            <div class="gauge-fill" data-unit="hours"></div>
                            <div class="gauge-center">
                                <div class="gauge-value" data-unit="hours">00</div>
                                <div class="gauge-label">${this.labels[1]}</div>
                            </div>
                        </div>
                    </div>
                    <div class="gauge-unit">
                        <div class="gauge-meter">
                            <div class="gauge-fill" data-unit="minutes"></div>
                            <div class="gauge-center">
                                <div class="gauge-value" data-unit="minutes">00</div>
                                <div class="gauge-label">${this.labels[2]}</div>
                            </div>
                        </div>
                    </div>
                    <div class="gauge-unit">
                        <div class="gauge-meter">
                            <div class="gauge-fill" data-unit="seconds"></div>
                            <div class="gauge-center">
                                <div class="gauge-value" data-unit="seconds">00</div>
                                <div class="gauge-label">${this.labels[3]}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    renderCardFlip() {
        this.container.innerHTML = `
            <div class="countdown-display flip-display">
                <div class="flip-container">
                    <div class="flip-unit">
                        <div class="flip-card">
                            <div class="flip-card-inner">
                                <div class="flip-card-front">
                                    <span class="flip-value" data-unit="days">00</span>
                                </div>
                                <div class="flip-card-back">
                                    <span class="flip-value" data-unit="days">00</span>
                                </div>
                            </div>
                        </div>
                        <div class="flip-label">${this.labels[0]}</div>
                    </div>
                    <div class="flip-unit">
                        <div class="flip-card">
                            <div class="flip-card-inner">
                                <div class="flip-card-front">
                                    <span class="flip-value" data-unit="hours">00</span>
                                </div>
                                <div class="flip-card-back">
                                    <span class="flip-value" data-unit="hours">00</span>
                                </div>
                            </div>
                        </div>
                        <div class="flip-label">${this.labels[1]}</div>
                    </div>
                    <div class="flip-unit">
                        <div class="flip-card">
                            <div class="flip-card-inner">
                                <div class="flip-card-front">
                                    <span class="flip-value" data-unit="minutes">00</span>
                                </div>
                                <div class="flip-card-back">
                                    <span class="flip-value" data-unit="minutes">00</span>
                                </div>
                            </div>
                        </div>
                        <div class="flip-label">${this.labels[2]}</div>
                    </div>
                    <div class="flip-unit">
                        <div class="flip-card">
                            <div class="flip-card-inner">
                                <div class="flip-card-front">
                                    <span class="flip-value" data-unit="seconds">00</span>
                                </div>
                                <div class="flip-card-back">
                                    <span class="flip-value" data-unit="seconds">00</span>
                                </div>
                            </div>
                        </div>
                        <div class="flip-label">${this.labels[3]}</div>
                    </div>
                </div>
            </div>
        `;
    }
    
    renderSlideAnimation() {
        this.container.innerHTML = `
            <div class="countdown-display slide-display">
                <div class="slide-container">
                    <div class="slide-unit">
                        <div class="slide-wrapper">
                            <div class="slide-value" data-unit="days">00</div>
                        </div>
                        <div class="slide-label">${this.labels[0]}</div>
                    </div>
                    <div class="slide-separator">:</div>
                    <div class="slide-unit">
                        <div class="slide-wrapper">
                            <div class="slide-value" data-unit="hours">00</div>
                        </div>
                        <div class="slide-label">${this.labels[1]}</div>
                    </div>
                    <div class="slide-separator">:</div>
                    <div class="slide-unit">
                        <div class="slide-wrapper">
                            <div class="slide-value" data-unit="minutes">00</div>
                        </div>
                        <div class="slide-label">${this.labels[2]}</div>
                    </div>
                    <div class="slide-separator">:</div>
                    <div class="slide-unit">
                        <div class="slide-wrapper">
                            <div class="slide-value" data-unit="seconds">00</div>
                        </div>
                        <div class="slide-label">${this.labels[3]}</div>
                    </div>
                </div>
            </div>
        `;
    }
    
    renderGlassmorphism() {
        this.container.innerHTML = `
            <div class="countdown-display glass-display">
                <div class="glass-container">
                    <div class="glass-unit">
                        <div class="glass-card">
                            <div class="glass-value" data-unit="days">00</div>
                            <div class="glass-label">${this.labels[0]}</div>
                        </div>
                    </div>
                    <div class="glass-unit">
                        <div class="glass-card">
                            <div class="glass-value" data-unit="hours">00</div>
                            <div class="glass-label">${this.labels[1]}</div>
                        </div>
                    </div>
                    <div class="glass-unit">
                        <div class="glass-card">
                            <div class="glass-value" data-unit="minutes">00</div>
                            <div class="glass-label">${this.labels[2]}</div>
                        </div>
                    </div>
                    <div class="glass-unit">
                        <div class="glass-card">
                            <div class="glass-value" data-unit="seconds">00</div>
                            <div class="glass-label">${this.labels[3]}</div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    renderNeumorphism() {
        this.container.innerHTML = `
            <div class="countdown-display neuro-display">
                <div class="neuro-container">
                    <div class="neuro-unit">
                        <div class="neuro-card">
                            <div class="neuro-value" data-unit="days">00</div>
                            <div class="neuro-label">${this.labels[0]}</div>
                        </div>
                    </div>
                    <div class="neuro-unit">
                        <div class="neuro-card">
                            <div class="neuro-value" data-unit="hours">00</div>
                            <div class="neuro-label">${this.labels[1]}</div>
                        </div>
                    </div>
                    <div class="neuro-unit">
                        <div class="neuro-card">
                            <div class="neuro-value" data-unit="minutes">00</div>
                            <div class="neuro-label">${this.labels[2]}</div>
                        </div>
                    </div>
                    <div class="neuro-unit">
                        <div class="neuro-card">
                            <div class="neuro-value" data-unit="seconds">00</div>
                            <div class="neuro-label">${this.labels[3]}</div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    renderGradient() {
        this.container.innerHTML = `
            <div class="countdown-display gradient-display">
                <div class="gradient-container">
                    <div class="gradient-unit">
                        <div class="gradient-card">
                            <div class="gradient-value" data-unit="days">00</div>
                            <div class="gradient-label">${this.labels[0]}</div>
                        </div>
                    </div>
                    <div class="gradient-unit">
                        <div class="gradient-card">
                            <div class="gradient-value" data-unit="hours">00</div>
                            <div class="gradient-label">${this.labels[1]}</div>
                        </div>
                    </div>
                    <div class="gradient-unit">
                        <div class="gradient-card">
                            <div class="gradient-value" data-unit="minutes">00</div>
                            <div class="gradient-label">${this.labels[2]}</div>
                        </div>
                    </div>
                    <div class="gradient-unit">
                        <div class="gradient-card">
                            <div class="gradient-value" data-unit="seconds">00</div>
                            <div class="gradient-label">${this.labels[3]}</div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    renderMinimalist() {
        this.container.innerHTML = `
            <div class="countdown-display minimal-display">
                <div class="minimal-container">
                    <div class="minimal-unit">
                        <div class="minimal-value" data-unit="days">00</div>
                        <div class="minimal-label">${this.labels[0]}</div>
                    </div>
                    <div class="minimal-separator">:</div>
                    <div class="minimal-unit">
                        <div class="minimal-value" data-unit="hours">00</div>
                        <div class="minimal-label">${this.labels[1]}</div>
                    </div>
                    <div class="minimal-separator">:</div>
                    <div class="minimal-unit">
                        <div class="minimal-value" data-unit="minutes">00</div>
                        <div class="minimal-label">${this.labels[2]}</div>
                    </div>
                    <div class="minimal-separator">:</div>
                    <div class="minimal-unit">
                        <div class="minimal-value" data-unit="seconds">00</div>
                        <div class="minimal-label">${this.labels[3]}</div>
                    </div>
                </div>
            </div>
        `;
    }
    
    updateDisplay(timeLeft) {
        const values = {
            days: String(timeLeft.days).padStart(2, '0'),
            hours: String(timeLeft.hours).padStart(2, '0'),
            minutes: String(timeLeft.minutes).padStart(2, '0'),
            seconds: String(timeLeft.seconds).padStart(2, '0')
        };
        
        // Update all time values
        Object.keys(values).forEach(unit => {
            const elements = this.container.querySelectorAll(`[data-unit="${unit}"]`);
            elements.forEach(element => {
                if (element.tagName === 'CIRCLE') {
                    // Handle circular progress
                    this.updateCircularProgress(element, timeLeft[unit], unit);
                } else if (element.classList.contains('gauge-fill')) {
                    // Handle gauge progress
                    this.updateGaugeProgress(element, timeLeft[unit], unit);
                } else {
                    // Handle text values with animation
                    this.updateTextValue(element, values[unit]);
                }
            });
        });
    }
    
    updateTextValue(element, newValue) {
        if (element.textContent !== newValue) {
            // Add animation class for smooth transitions
            element.style.transform = 'scale(1.1)';
            element.textContent = newValue;
            
            setTimeout(() => {
                element.style.transform = 'scale(1)';
            }, 150);
        }
    }
    
    updateCircularProgress(circle, value, unit) {
        const maxValues = { days: 365, hours: 24, minutes: 60, seconds: 60 };
        const percentage = (value / maxValues[unit]) * 100;
        const circumference = 2 * Math.PI * 50; // radius = 50
        const offset = circumference - (percentage / 100) * circumference;
        
        circle.style.strokeDasharray = circumference;
        circle.style.strokeDashoffset = offset;
    }
    
    updateGaugeProgress(gauge, value, unit) {
        const maxValues = { days: 365, hours: 24, minutes: 60, seconds: 60 };
        const percentage = (value / maxValues[unit]) * 100;
        gauge.style.transform = `rotate(${(percentage / 100) * 180 - 90}deg)`;
    }
    
    renderCompleted() {
        this.container.innerHTML = `
            <div class="countdown-completed">
                <div class="completed-message">Time's Up!</div>
            </div>
        `;
    }
}

// Export for use
window.SeeGapCountdown = SeeGapCountdown;
