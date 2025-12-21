<div class="space-y-4">
    <div class="text-sm text-gray-700">
        Esta pantalla permite generar y guardar el <span class="font-medium">JSON‑LD</span> (schema.org) por producto e idioma.
    </div>

    <div class="code-snippet">
        <div style="color: #9CA3AF;">// Filament Resource</div>
        <div>
            <span style="color: #EC4899;">Resource</span>
            <span style="color: #6B7280;">:</span>
            <span style="color: #8B5CF6;">StructuredDataResource</span>
        </div>
        <div style="color: #9CA3AF;" class="mt-4">// Route</div>
        <div>
            <span style="color: #F59E0B;">admin/structured-data-products</span>
        </div>
        <div style="color: #9CA3AF;" class="mt-4">// Data Flow</div>
        <div>
            <span style="color: #8B5CF6;">Product</span>
            <span style="color: #6B7280;">-&gt;</span>
            <span style="color: #8B5CF6;">structuredDataRecords()</span>
            <span style="color: #6B7280;"> =&gt; </span>
            <span style="color: #8B5CF6;">soft_structured_data</span>
        </div>
        <div style="color: #9CA3AF;" class="mt-4">// Action</div>
        <div>
            <span style="color: #EC4899;">updateOrCreate</span>
            <span style="color: #6B7280;">(</span>
            <span style="color: #6B7280;">product_id</span>
            <span style="color: #6B7280;">,</span>
            <span style="color: #6B7280;">lang_id</span>
            <span style="color: #6B7280;">)</span>
        </div>
    </div>
</div>
