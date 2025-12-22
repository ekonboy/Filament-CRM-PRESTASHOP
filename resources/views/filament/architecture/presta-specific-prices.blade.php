<div class="space-y-6" style="font-family: system-ui, -apple-system, sans-serif;">
    <!-- Introducción -->
     <div class="code-snippet">
    <div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2" style="font-size: 18px; font-weight: bold; color: #333; margin-bottom: 8px;">¿Qué es PrestaSpecificPrice Resource?</h3>
        <p class="text-gray-600" style="color: #666;">
            Este Resource permite gestionar descuentos específicos de PrestaShop de forma masiva. 
            Está diseñado para crear y administrar precios especiales para grupos de clientes 
            en toda la tienda, sin necesidad de aplicarlos a productos individuales.
        </p>
    </div>

    <!-- Estructura Principal -->
    <div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Estructura del Resource</h3>
        <div class="code-snippet" style="font-family: Monaco, Consolas, Courier New, monospace; font-size: 11px; line-height: 1.6; background: rgba(15, 23, 42, .8); backdrop-filter: blur(10px); border: 1px solid rgba(59, 130, 246, .2); border-radius: 8px; padding: 12px; box-shadow: 0 4px 20px #0000004d;">
            <div style="color: #9CA3AF;">// Resource Principal</div>
            <div><span style="color: #EC4899;">class</span> <span style="color: #8B5CF6;">PrestaSpecificPriceResource</span> <span style="color: #EC4899;">extends</span> <span style="color: #8B5CF6;">Resource</span></div>
            <div><span style="color: #6B7280;">{</span></div>
            <div>&nbsp;&nbsp;<span style="color: #EC4899;">protected static</span> <span style="color: #6B7280;">?</span><span style="color: #EC4899;">string</span> <span style="color: #6B7280;">$</span><span style="color: #8B5CF6;">model</span> <span style="color: #6B7280;">=</span> <span style="color: #8B5CF6;">PrestaSpecificPrice::class</span><span style="color: #6B7280;">;</span></div>
            <div>&nbsp;&nbsp;<span style="color: #EC4899;">protected static</span> <span style="color: #6B7280;">?</span><span style="color: #EC4899;">int</span> <span style="color: #6B7280;">$</span><span style="color: #8B5CF6;">navigationSort</span> <span style="color: #6B7280;">=</span> <span style="color: #F59E0B;">101</span><span style="color: #6B7280;">;</span></div>
            <div><span style="color: #6B7280;">}</span></div>
        </div>
    </div>

    <!-- Modelos Relacionados -->
    <div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Modelos de Datos</h3>
        
        <div class="mb-4">
            <h4 class="font-medium text-gray-800 mb-1">PrestaSpecificPrice</h4>
            <div class="code-snippet" style="font-family: Monaco, Consolas, Courier New, monospace; font-size: 11px; line-height: 1.6; background: rgba(15, 23, 42, .8); backdrop-filter: blur(10px); border: 1px solid rgba(59, 130, 246, .2); border-radius: 8px; padding: 12px; box-shadow: 0 4px 20px #0000004d;">
                <div style="color: #9CA3AF;">// Modelo principal de descuentos</div>
                <div><span style="color: #EC4899;">class</span> <span style="color: #8B5CF6;">PrestaSpecificPrice</span> <span style="color: #EC4899;">extends</span> <span style="color: #8B5CF6;">Model</span></div>
                <div><span style="color: #6B7280;">{</span></div>
                <div>&nbsp;&nbsp;<span style="color: #EC4899;">protected</span> <span style="color: #6B7280;">$</span><span style="color: #8B5CF6;">table</span> <span style="color: #6B7280;">=</span> <span style="color: #10B981;">'soft_specific_price'</span><span style="color: #6B7280;">;</span></div>
                <div>&nbsp;&nbsp;<span style="color: #EC4899;">protected</span> <span style="color: #6B7280;">$</span><span style="color: #8B5CF6;">primaryKey</span> <span style="color: #6B7280;">=</span> <span style="color: #10B981;">'id_specific_price'</span><span style="color: #6B7280;">;</span></div>
                <div>&nbsp;&nbsp;<span style="color: #EC4899;">public</span> <span style="color: #6B7280;">$</span><span style="color: #8B5CF6;">timestamps</span> <span style="color: #6B7280;">=</span> <span style="color: #EC4899;">false</span><span style="color: #6B7280;">;</span></div>
                <div><span style="color: #6B7280;">}</span></div>
            </div>
        </div>

        <div class="mb-4">
            <h4 class="font-medium text-gray-800 mb-1">CustomPromoText</h4>
            <div class="code-snippet" style="font-family: Monaco, Consolas, Courier New, monospace; font-size: 11px; line-height: 1.6; background: rgba(15, 23, 42, .8); backdrop-filter: blur(10px); border: 1px solid rgba(59, 130, 246, .2); border-radius: 8px; padding: 12px; box-shadow: 0 4px 20px #0000004d;">
                <div style="color: #9CA3AF;">// Modelo para textos promocionales</div>
                <div><span style="color: #EC4899;">class</span> <span style="color: #8B5CF6;">CustomPromoText</span> <span style="color: #EC4899;">extends</span> <span style="color: #8B5CF6;">Model</span></div>
                <div><span style="color: #6B7280;">{</span></div>
                <div>&nbsp;&nbsp;<span style="color: #EC4899;">protected</span> <span style="color: #6B7280;">$</span><span style="color: #8B5CF6;">table</span> <span style="color: #6B7280;">=</span> <span style="color: #10B981;">'soft_custom_promo_text'</span><span style="color: #6B7280;">;</span></div>
                <div>&nbsp;&nbsp;<span style="color: #EC4899;">public</span> <span style="color: #6B7280;">$</span><span style="color: #8B5CF6;">incrementing</span> <span style="color: #6B7280;">=</span> <span style="color: #EC4899;">false</span><span style="color: #6B7280;">;</span></div>
                <div><span style="color: #6B7280;">}</span></div>
            </div>
        </div>
    </div>

    <!-- Funcionalidades Principales -->
    <div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Funcionalidades Principales</h3>
        <ul class="list-disc list-inside space-y-1 text-gray-600">
            <li><strong>Creación de Descuentos:</strong> Formulario para crear nuevos descuentos por grupo</li>
            <li><strong>Gestión Dinámica:</strong> Carga grupos de clientes desde la base de datos</li>
            <li><strong>Textos Personalizados:</strong> Mensajes promocionales asociados a cada descuento</li>
            <li><strong>Edición y Eliminación:</strong> Acciones directas en la tabla</li>
            <li><strong>Integración PrestaShop:</strong> Compatible con estructura nativa de PrestaShop</li>
        </ul>
    </div>

    <!-- Query de Verificación -->
    <div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Query para Verificar Datos</h3>
        <div class="code-snippet" style="font-family: Monaco, Consolas, Courier New, monospace; font-size: 11px; line-height: 1.6; background: rgba(15, 23, 42, .8); backdrop-filter: blur(10px); border: 1px solid rgba(59, 130, 246, .2); border-radius: 8px; padding: 12px; box-shadow: 0 4px 20px #0000004d;">
            <div style="color: #9CA3AF;">-- Ver descuentos activos</div>
            <div><span style="color: #8B5CF6;">SELECT</span> sp<span style="color: #6B7280;">.</span>id_specific_price<span style="color: #6B7280;">,</span> sp<span style="color: #6B7280;">.</span>id_group<span style="color: #6B7280;">,</span></div>
            <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #6B7280;">(</span>sp<span style="color: #6B7280;">.</span>reduction <span style="color: #6B7280;">*</span> <span style="color: #F59E0B;">100</span><span style="color: #6B7280;">)</span> <span style="color: #8B5CF6;">AS</span> porcentaje<span style="color: #6B7280;">,</span></div>
            <div>&nbsp;&nbsp;&nbsp;&nbsp;sp<span style="color: #6B7280;">.</span><span style="color: #8B5CF6;">from</span><span style="color: #6B7280;">,</span> sp<span style="color: #6B7280;">.</span><span style="color: #8B5CF6;">to</span><span style="color: #6B7280;">,</span></div>
            <div>&nbsp;&nbsp;&nbsp;&nbsp;cpt<span style="color: #6B7280;">.</span>promo_text</div>
            <div><span style="color: #8B5CF6;">FROM</span> soft_specific_price sp</div>
            <div><span style="color: #8B5CF6;">LEFT JOIN</span> soft_custom_promo_text cpt</div>
            <div>&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #8B5CF6;">ON</span> sp<span style="color: #6B7280;">.</span>id_specific_price <span style="color: #6B7280;">=</span> cpt<span style="color: #6B7280;">.</span>id_specific_price</div>
            <div><span style="color: #8B5CF6;">WHERE</span> sp<span style="color: #6B7280;">.</span>id_product <span style="color: #6B7280;">=</span> <span style="color: #F59E0B;">0</span></div>
            <div><span style="color: #8B5CF6;">ORDER BY</span> sp<span style="color: #6B7280;">.</span>id_specific_price <span style="color: #8B5CF6;">DESC</span><span style="color: #6B7280;">;</span></div>
        </div>
    </div>

    <!-- Características Técnicas -->
    <div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Características Técnicas</h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <h4 class="font-medium text-gray-800">Base de Datos</h4>
                <ul class="text-gray-600 mt-1">
                    <li>• Tabla: soft_specific_price</li>
                    <li>• Tabla: soft_custom_promo_text</li>
                    <li>• Relación 1:1 entre tablas</li>
                </ul>
            </div>
            <div>
                <h4 class="font-medium text-gray-800">Filament v4</h4>
                <ul class="text-gray-600 mt-1">
                    <li>• Schema para formularios</li>
                    <li>• Actions para botones</li>
                    <li>• Components dinámicos</li>
                </ul>
            </div>
        </div>
    </div>

                </div>
</div>