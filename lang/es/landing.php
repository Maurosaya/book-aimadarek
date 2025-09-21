<?php

return [
    // Meta tags
    'meta' => [
        'title' => 'Aimadarek Book - Agente de IA + Sistema de Reservas Multivertical',
        'description' => 'Sistema inteligente de reservas para restaurantes, barberías, salones de belleza y clínicas dentales. Integración con IA, WhatsApp, webhooks y soporte multilingüe.',
        'keywords' => 'reservas, restaurantes, barberías, salones, dentales, IA, WhatsApp, multitenant, API',
    ],

    // Navigation
    'nav' => [
        'home' => 'Inicio',
        'features' => 'Características',
        'demo' => 'Demo',
        'how_it_works' => 'Cómo Funciona',
        'api' => 'API',
        'contact' => 'Contacto',
    ],

    // Hero section
    'hero' => [
        'title' => 'Agente de IA + Sistema de Reservas',
        'subtitle' => 'La solución inteligente para restaurantes, barberías, salones de belleza y clínicas dentales. Automatiza tus reservas con IA, WhatsApp y webhooks.',
        'cta_demo' => 'Probar Demo',
        'cta_contact' => 'Contactar',
        'scroll_down' => 'Ver más',
    ],

    // Features section
    'features' => [
        'title' => 'Características Principales',
        'subtitle' => 'Todo lo que necesitas para gestionar reservas de forma inteligente',
        
        'multitenant' => [
            'title' => 'Multitenant',
            'description' => 'Un solo sistema para múltiples negocios con aislamiento completo de datos.',
        ],
        
        'multi_vertical' => [
            'title' => 'Multi-nicho',
            'description' => 'Restaurantes, barberías, salones de belleza y clínicas dentales.',
        ],
        
        'ai_integration' => [
            'title' => 'Integración IA',
            'description' => 'Agente inteligente que optimiza horarios y gestiona reservas automáticamente.',
        ],
        
        'notifications' => [
            'title' => 'Recordatorios Inteligentes',
            'description' => 'WhatsApp, email y SMS con plantillas personalizables.',
        ],
        
        'webhooks' => [
            'title' => 'Webhooks HMAC',
            'description' => 'API REST v1 con webhooks seguros para integraciones.',
        ],
        
        'multilingual' => [
            'title' => 'Multilingüe',
            'description' => 'Soporte completo para español, inglés y holandés.',
        ],
    ],

    // Verticals section
    'verticals' => [
        'title' => 'Sectores Soportados',
        'subtitle' => 'Adaptado para diferentes tipos de negocios',
        
        'restaurant' => [
            'title' => 'Restaurantes',
            'description' => 'Gestión de mesas, grupos y disponibilidad con algoritmos inteligentes.',
        ],
        
        'barber' => [
            'title' => 'Barberías',
            'description' => 'Programación de personal y servicios con recordatorios automáticos.',
        ],
        
        'beauty' => [
            'title' => 'Salones de Belleza',
            'description' => 'Gestión de sillas, tratamientos y disponibilidad de estilistas.',
        ],
        
        'dental' => [
            'title' => 'Clínicas Dentales',
            'description' => 'Coordinación de salas, dentistas y equipos especializados.',
        ],
    ],

    // Demo section
    'demo' => [
        'title' => 'Demo Interactivo',
        'subtitle' => 'Prueba el widget de reservas en acción',
        'widget_title' => 'Sistema de Reservas',
        'widget_subtitle' => 'Selecciona fecha y hora para tu reserva',
        'demo_mode' => 'Modo Demo',
        'demo_message' => 'Este es un demo. Para usar en producción, configura un servicio en el panel de administración.',
        'view_schedule' => 'Ver Horarios',
        'no_data' => 'Sin datos de demo disponibles',
        'configure_service' => 'Configurar Servicio',
    ],

    // How it works section
    'how_it_works' => [
        'title' => 'Cómo Funciona',
        'subtitle' => 'Tres pasos simples para empezar',
        
        'step1' => [
            'title' => '1. Elige Servicio y Fecha',
            'description' => 'Selecciona el servicio que necesitas y la fecha disponible.',
        ],
        
        'step2' => [
            'title' => '2. Confirma tu Reserva',
            'description' => 'Completa tus datos y confirma la reserva.',
        ],
        
        'step3' => [
            'title' => '3. Recibe Confirmación',
            'description' => 'Obtén confirmación por email y recordatorios automáticos.',
        ],
    ],

    // API section
    'api' => [
        'title' => 'Integración API',
        'subtitle' => 'Conecta tu sistema con nuestra API REST v1',
        'description' => 'API completa con autenticación Sanctum, webhooks HMAC y soporte multilingüe.',
        'view_docs' => 'Ver Documentación',
        'test_endpoint' => 'Probar Endpoint',
    ],

    // FAQ section
    'faq' => [
        'title' => 'Preguntas Frecuentes',
        'subtitle' => 'Respuestas a las preguntas más comunes',
        
        'q1' => [
            'question' => '¿Qué tipos de negocios pueden usar el sistema?',
            'answer' => 'El sistema está diseñado para restaurantes, barberías, salones de belleza y clínicas dentales, pero es flexible para adaptarse a otros negocios de servicios.',
        ],
        
        'q2' => [
            'question' => '¿Cómo funciona la integración con WhatsApp?',
            'answer' => 'El sistema envía recordatorios automáticos por WhatsApp usando plantillas personalizables y webhooks para notificaciones en tiempo real.',
        ],
        
        'q3' => [
            'question' => '¿Es seguro el sistema de webhooks?',
            'answer' => 'Sí, todos los webhooks están firmados con HMAC SHA-256 y incluyen reintentos automáticos con backoff exponencial.',
        ],
        
        'q4' => [
            'question' => '¿Puedo personalizar el widget de reservas?',
            'answer' => 'Sí, el widget es completamente personalizable y se puede integrar en cualquier sitio web con soporte multilingüe.',
        ],
        
        'q5' => [
            'question' => '¿Qué idiomas soporta el sistema?',
            'answer' => 'Actualmente soporta español, inglés y holandés, con planes de expandir a más idiomas.',
        ],
    ],

    // CTA section
    'cta' => [
        'title' => '¿Listo para Automatizar tus Reservas?',
        'subtitle' => 'Solicita una demo personalizada y descubre cómo podemos ayudarte',
        'button' => 'Solicitar Demo Personalizada',
    ],

    // Footer
    'footer' => [
        'description' => 'Sistema inteligente de reservas con IA para restaurantes, barberías, salones de belleza y clínicas dentales.',
        'links' => [
            'features' => 'Características',
            'demo' => 'Demo',
            'api' => 'API',
            'contact' => 'Contacto',
        ],
        'legal' => [
            'privacy' => 'Privacidad',
            'terms' => 'Términos',
            'cookies' => 'Cookies',
        ],
        'copyright' => '© :year :brand. Todos los derechos reservados.',
    ],

    // Common elements
    'common' => [
        'loading' => 'Cargando...',
        'error' => 'Error',
        'success' => 'Éxito',
        'close' => 'Cerrar',
        'open' => 'Abrir',
        'next' => 'Siguiente',
        'previous' => 'Anterior',
        'back' => 'Volver',
        'continue' => 'Continuar',
        'cancel' => 'Cancelar',
        'save' => 'Guardar',
        'edit' => 'Editar',
        'delete' => 'Eliminar',
        'view' => 'Ver',
        'download' => 'Descargar',
        'share' => 'Compartir',
        'copy' => 'Copiar',
        'search' => 'Buscar',
        'filter' => 'Filtrar',
        'sort' => 'Ordenar',
        'refresh' => 'Actualizar',
        'reload' => 'Recargar',
    ],
];
