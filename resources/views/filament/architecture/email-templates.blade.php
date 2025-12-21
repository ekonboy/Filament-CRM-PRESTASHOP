<div class="space-y-4">
    <div class="text-sm text-gray-700">
        Gestión de <span class="font-medium">plantillas de email</span> con traducciones por idioma. Se usan en acciones como el envío de tracking.
    </div>

    <div class="max-h-[60vh] overflow-auto pr-1 space-y-4">
        <div class="code-snippet">
            <div style="color: #9CA3AF;">// Filament Resource</div>
            <div>
                <span style="color: #EC4899;">Resource</span>
                <span style="color: #6B7280;">:</span>
                <span style="color: #8B5CF6;">EmailTemplateResource</span>
            </div>

            <div style="color: #9CA3AF;" class="mt-4">// Route</div>
            <div>
                <span style="color: #F59E0B;">admin/email-templates</span>
            </div>

            <div style="color: #9CA3AF;" class="mt-4">// Data Model</div>
            <div>
                <span style="color: #8B5CF6;">soft_email_templates</span>
                <span style="color: #6B7280;"> + </span>
                <span style="color: #8B5CF6;">soft_email_template_lang</span>
                <span style="color: #6B7280;"> (langs)</span>
            </div>

            <div style="color: #9CA3AF;" class="mt-4">// Editing pattern</div>
            <div>
                <span style="color: #6B7280;">Se edita </span>
                <span style="color: #8B5CF6;">subject</span>
                <span style="color: #6B7280;"> y </span>
                <span style="color: #8B5CF6;">html_content</span>
                <span style="color: #6B7280;"> para un idioma seleccionado y se sincroniza en </span>
                <span style="color: #8B5CF6;">translations</span>
                <span style="color: #6B7280;"> (Hidden)</span>
            </div>
        </div>

        <div class="code-snippet">
            <div style="color: #9CA3AF;">// Used by</div>
            <div>
                <span style="color: #8B5CF6;">OrderTrackingResource</span>
                <span style="color: #6B7280;"> action </span>
                <span style="color: #8B5CF6;">send_tracking</span>
                <span style="color: #6B7280;"> =&gt; Mail::html(...)</span>
            </div>

            <div style="color: #9CA3AF;" class="mt-4">// Placeholders (typical)</div>
            <div>
                <span style="color: #6B7280;">{reference} {tracking_code} {order_id}</span>
            </div>
        </div>
    </div>
</div>
