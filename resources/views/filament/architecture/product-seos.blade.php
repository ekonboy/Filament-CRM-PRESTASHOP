<div class="space-y-4">
    <div class="text-sm text-gray-700">
        Esta pantalla centraliza la edición de <span class="font-medium">SEO por producto</span> (por idioma) y valida los campos clave.
    </div>

    <div class="max-h-[60vh] overflow-auto pr-1 space-y-4">
        <div class="code-snippet">
            <div style="color: #9CA3AF;">// Filament Resource</div>
            <div>
                <span style="color: #EC4899;">Resource</span>
                <span style="color: #6B7280;">:</span>
                <span style="color: #8B5CF6;">ProductSeoResource</span>
            </div>

            <div style="color: #9CA3AF;" class="mt-4">// Route</div>
            <div>
                <span style="color: #F59E0B;">admin/product-seos</span>
            </div>

            <div style="color: #9CA3AF;" class="mt-4">// Data Flow</div>
            <div>
                <span style="color: #8B5CF6;">Product</span>
                <span style="color: #6B7280;"> (soft_product)</span>
                <span style="color: #6B7280;"> -&gt; </span>
                <span style="color: #8B5CF6;">langs</span>
                <span style="color: #6B7280;"> =&gt; </span>
                <span style="color: #8B5CF6;">soft_product_lang</span>
            </div>

            <div style="color: #9CA3AF;" class="mt-4">// What you edit</div>
            <div>
                <span style="color: #8B5CF6;">link_rewrite</span>
                <span style="color: #6B7280;">,</span>
                <span style="color: #8B5CF6;">meta_title</span>
                <span style="color: #6B7280;">,</span>
                <span style="color: #8B5CF6;">meta_description</span>
                <span style="color: #6B7280;">,</span>
                <span style="color: #8B5CF6;">meta_keywords</span>
            </div>

            <div style="color: #9CA3AF;" class="mt-4">// Persistence</div>
            <div>
                <span style="color: #EC4899;">updateOrInsert</span>
                <span style="color: #6B7280;">(</span>
                <span style="color: #8B5CF6;">id_product</span>
                <span style="color: #6B7280;">,</span>
                <span style="color: #8B5CF6;">id_shop</span>
                <span style="color: #6B7280;">,</span>
                <span style="color: #8B5CF6;">id_lang</span>
                <span style="color: #6B7280;">)</span>
            </div>
        </div>

        <div class="code-snippet">
            <div style="color: #9CA3AF;">// UI notes</div>
            <div>
                <span style="color: #6B7280;">Referencia es </span>
                <span style="color: #8B5CF6;">copyable()</span>
                <span style="color: #6B7280;"> (sin iconos)</span>
            </div>
            <div>
                <span style="color: #6B7280;">Edición por idiomas: repeater </span>
                <span style="color: #8B5CF6;">langs</span>
            </div>
        </div>
    </div>
</div>
