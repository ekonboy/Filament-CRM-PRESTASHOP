# Exitus Management System (EMS) 🚀
### Laravel 12 + Filament 4.3 + PrestaShop 8 Core

Este proyecto es un **Panel de Control Avanzado (PIM/CRM)** diseñado para potenciar y optimizar una tienda PrestaShop 8 alojada en Siteground. Mientras que PrestaShop gestiona la venta al público, este sistema actúa como el "Cerebro de Gestión" para operaciones masivas, SEO, analítica de beneficios y automatización.

## 🛠️ Stack Tecnológico
- **Framework:** Laravel 12 (PHP 8.3+)
- **Panel Administrativo:** Filament 4.3 (TALL Stack)
- **Base de Datos:** MySQL (Conexión directa a tablas `soft_` de PrestaShop)
- **Infraestructura:** Siteground Hosting (SSH, Git, Cron Jobs)

## 🌟 Características Principales

### 1. Gestión de Precios Masivos (Bulk Pricing)
- **Lógica de Grupos:** Aplicación de descuentos porcentuales por grupo de clientes con una sola regla, evitando la saturación de la base de datos.
- **Cálculo Off-load:** Los cálculos se procesan en el subdominio de Laravel mediante Jobs, manteniendo la tienda principal rápida.
- **Safe-Guard:** El precio base de PrestaShop (`soft_product.price`) es sagrado; los descuentos se gestionan en una capa lógica superior para permitir rollbacks instantáneos.

### 2. Dashboard de Diagnóstico de Catálogo
- **Health Check:** Identificación visual de productos activos sin imágenes, sin stock o con SEO incompleto.
- **Stock Real-Time:** Conexión directa a la tabla `soft_stock_available` para monitorizar el inventario físico real por combinaciones.

### 3. Optimización SEO y Marketing
- **Tracking Avanzado:** Herramientas de seguimiento de conversiones y comportamiento de usuario.
- **Generación de Contenido:** (Próximamente) Integración con IA para descripciones automáticas.
- **Mailings Segmentados:** Lógica de segmentación de clientes basada en el historial de pedidos de PrestaShop.

## 🚀 Instalación y Despliegue (Siteground)

1. **Clonar y Subir:**
   ```bash
   git clone [https://github.com/tu-usuario/exitus-manager.git](https://github.com/tu-usuario/exitus-manager.git)
