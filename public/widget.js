/**
 * Booking Widget Demo
 * 
 * Simple demo widget for the landing page
 * In production, this would be a more sophisticated widget
 */

(function() {
    'use strict';

    // Widget configuration
    const config = {
        apiUrl: '/api/v1',
        locale: document.documentElement.lang || 'en',
        theme: 'auto', // auto, light, dark
    };

    // Widget class
    class BookingWidget {
        constructor(containerId, options = {}) {
            this.container = document.getElementById(containerId);
            this.options = { ...config, ...options };
            this.isLoaded = false;
            
            if (this.container) {
                this.init();
            }
        }

        init() {
            this.render();
            this.bindEvents();
            this.isLoaded = true;
        }

        render() {
            if (!this.container) return;

            this.container.innerHTML = `
                <div class="booking-widget">
                    <div class="widget-header">
                        <h3>${this.getText('widget_title')}</h3>
                        <p>${this.getText('widget_subtitle')}</p>
                    </div>
                    
                    <div class="widget-content">
                        <div class="demo-notice">
                            <div class="notice-icon">🎯</div>
                            <h4>${this.getText('demo_mode')}</h4>
                            <p>${this.getText('demo_message')}</p>
                            <button class="demo-button" onclick="this.showAvailability()">
                                ${this.getText('view_schedule')}
                            </button>
                        </div>
                        
                        <div class="availability-section" style="display: none;">
                            <h4>${this.getText('available_times')}</h4>
                            <div class="time-slots">
                                <div class="time-slot">09:00</div>
                                <div class="time-slot">10:30</div>
                                <div class="time-slot">14:00</div>
                                <div class="time-slot">16:30</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Add styles
            this.addStyles();
        }

        addStyles() {
            if (document.getElementById('booking-widget-styles')) return;

            const styles = document.createElement('style');
            styles.id = 'booking-widget-styles';
            styles.textContent = `
                .booking-widget {
                    font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif;
                    max-width: 400px;
                    margin: 0 auto;
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                    overflow: hidden;
                }
                
                .dark .booking-widget {
                    background: #1f2937;
                    color: #f9fafb;
                }
                
                .widget-header {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    padding: 1.5rem;
                    text-align: center;
                }
                
                .widget-header h3 {
                    margin: 0 0 0.5rem 0;
                    font-size: 1.25rem;
                    font-weight: 600;
                }
                
                .widget-header p {
                    margin: 0;
                    opacity: 0.9;
                    font-size: 0.875rem;
                }
                
                .widget-content {
                    padding: 1.5rem;
                }
                
                .demo-notice {
                    text-align: center;
                }
                
                .notice-icon {
                    font-size: 2rem;
                    margin-bottom: 1rem;
                }
                
                .demo-notice h4 {
                    margin: 0 0 0.5rem 0;
                    font-size: 1.125rem;
                    font-weight: 600;
                    color: #374151;
                }
                
                .dark .demo-notice h4 {
                    color: #f9fafb;
                }
                
                .demo-notice p {
                    margin: 0 0 1.5rem 0;
                    color: #6b7280;
                    font-size: 0.875rem;
                    line-height: 1.5;
                }
                
                .dark .demo-notice p {
                    color: #d1d5db;
                }
                
                .demo-button {
                    background: #3b82f6;
                    color: white;
                    border: none;
                    padding: 0.75rem 1.5rem;
                    border-radius: 0.5rem;
                    font-weight: 500;
                    cursor: pointer;
                    transition: background-color 0.2s;
                }
                
                .demo-button:hover {
                    background: #2563eb;
                }
                
                .availability-section h4 {
                    margin: 0 0 1rem 0;
                    font-size: 1.125rem;
                    font-weight: 600;
                    color: #374151;
                }
                
                .dark .availability-section h4 {
                    color: #f9fafb;
                }
                
                .time-slots {
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                    gap: 0.75rem;
                }
                
                .time-slot {
                    background: #f3f4f6;
                    border: 1px solid #e5e7eb;
                    border-radius: 0.5rem;
                    padding: 0.75rem;
                    text-align: center;
                    font-weight: 500;
                    cursor: pointer;
                    transition: all 0.2s;
                }
                
                .dark .time-slot {
                    background: #374151;
                    border-color: #4b5563;
                    color: #f9fafb;
                }
                
                .time-slot:hover {
                    background: #e5e7eb;
                    border-color: #3b82f6;
                }
                
                .dark .time-slot:hover {
                    background: #4b5563;
                }
            `;

            document.head.appendChild(styles);
        }

        bindEvents() {
            // Bind demo button click
            const demoButton = this.container.querySelector('.demo-button');
            if (demoButton) {
                demoButton.addEventListener('click', () => this.showAvailability());
            }

            // Bind time slot clicks
            this.container.addEventListener('click', (e) => {
                if (e.target.classList.contains('time-slot')) {
                    this.selectTimeSlot(e.target);
                }
            });
        }

        showAvailability() {
            const availabilitySection = this.container.querySelector('.availability-section');
            const demoNotice = this.container.querySelector('.demo-notice');
            
            if (availabilitySection && demoNotice) {
                demoNotice.style.display = 'none';
                availabilitySection.style.display = 'block';
            }
        }

        selectTimeSlot(slot) {
            // Remove previous selection
            this.container.querySelectorAll('.time-slot').forEach(s => {
                s.style.background = '';
                s.style.borderColor = '';
            });

            // Highlight selected slot
            slot.style.background = '#dbeafe';
            slot.style.borderColor = '#3b82f6';

            // Show booking form (simplified)
            this.showBookingForm(slot.textContent);
        }

        showBookingForm(time) {
            const form = document.createElement('div');
            form.className = 'booking-form';
            form.innerHTML = `
                <div style="margin-top: 1rem; padding: 1rem; background: #f9fafb; border-radius: 0.5rem;">
                    <h5 style="margin: 0 0 0.5rem 0; font-weight: 600;">${this.getText('booking_form_title')}</h5>
                    <p style="margin: 0 0 1rem 0; font-size: 0.875rem; color: #6b7280;">
                        ${this.getText('selected_time')}: ${time}
                    </p>
                    <div style="display: flex; gap: 0.5rem;">
                        <input type="text" placeholder="${this.getText('your_name')}" 
                               style="flex: 1; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        <button onclick="this.confirmBooking()" 
                                style="background: #10b981; color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.375rem; font-weight: 500;">
                            ${this.getText('confirm')}
                        </button>
                    </div>
                </div>
            `;

            this.container.querySelector('.widget-content').appendChild(form);
        }

        confirmBooking() {
            alert(this.getText('booking_confirmed'));
        }

        getText(key) {
            const texts = {
                'widget_title': {
                    'en': 'Booking System',
                    'es': 'Sistema de Reservas',
                    'nl': 'Boekingssysteem'
                },
                'widget_subtitle': {
                    'en': 'Select date and time for your booking',
                    'es': 'Selecciona fecha y hora para tu reserva',
                    'nl': 'Selecteer datum en tijd voor je boeking'
                },
                'demo_mode': {
                    'en': 'Demo Mode',
                    'es': 'Modo Demo',
                    'nl': 'Demo Modus'
                },
                'demo_message': {
                    'en': 'This is a demo. To use in production, configure a service in the admin panel.',
                    'es': 'Este es un demo. Para usar en producción, configura un servicio en el panel de administración.',
                    'nl': 'Dit is een demo. Voor productiegebruik, configureer een service in het beheerpaneel.'
                },
                'view_schedule': {
                    'en': 'View Schedule',
                    'es': 'Ver Horarios',
                    'nl': 'Bekijk Schema'
                },
                'available_times': {
                    'en': 'Available Times',
                    'es': 'Horarios Disponibles',
                    'nl': 'Beschikbare Tijden'
                },
                'booking_form_title': {
                    'en': 'Complete Your Booking',
                    'es': 'Completa tu Reserva',
                    'nl': 'Voltooi je Boeking'
                },
                'selected_time': {
                    'en': 'Selected Time',
                    'es': 'Hora Seleccionada',
                    'nl': 'Geselecteerde Tijd'
                },
                'your_name': {
                    'en': 'Your Name',
                    'es': 'Tu Nombre',
                    'nl': 'Je Naam'
                },
                'confirm': {
                    'en': 'Confirm',
                    'es': 'Confirmar',
                    'nl': 'Bevestigen'
                },
                'booking_confirmed': {
                    'en': 'Booking confirmed! You will receive a confirmation email.',
                    'es': '¡Reserva confirmada! Recibirás un email de confirmación.',
                    'nl': 'Boeking bevestigd! Je ontvangt een bevestigingsmail.'
                }
            };

            const locale = this.options.locale;
            return texts[key]?.[locale] || texts[key]?.['en'] || key;
        }
    }

    // Auto-initialize widget if container exists
    document.addEventListener('DOMContentLoaded', function() {
        const widgetContainer = document.getElementById('reservas-widget');
        if (widgetContainer) {
            new BookingWidget('reservas-widget');
        }
    });

    // Export for manual initialization
    window.BookingWidget = BookingWidget;

})();
