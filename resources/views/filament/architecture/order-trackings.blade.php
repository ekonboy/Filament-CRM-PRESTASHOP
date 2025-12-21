<div class="space-y-4">
    <div class="text-sm text-gray-700">
        Esta pantalla centraliza el envío del <span class="font-medium">tracking</span> al cliente usando plantillas de email y guarda un registro por pedido.
    </div>

    <div class="max-h-[60vh] overflow-auto pr-1 space-y-4">
        <div class="code-snippet">
            <div style="color: #9CA3AF;">// Filament Resource</div>
            <div>
                <span style="color: #EC4899;">Resource</span>
                <span style="color: #6B7280;">:</span>
                <span style="color: #8B5CF6;">OrderTrackingResource</span>
            </div>

            <div style="color: #9CA3AF;" class="mt-4">// Route</div>
            <div>
                <span style="color: #F59E0B;">admin/order-trackings</span>
            </div>

            <div style="color: #9CA3AF;" class="mt-4">// Data Flow</div>
            <div>
                <span style="color: #8B5CF6;">Order</span>
                <span style="color: #6B7280;"> (soft_orders)</span>
                <span style="color: #6B7280;"> -&gt; </span>
                <span style="color: #8B5CF6;">Action: send_tracking</span>
            </div>
            <div>
                <span style="color: #EC4899;">OrderTracking</span>
                <span style="color: #6B7280;"> -&gt; </span>
                <span style="color: #8B5CF6;">soft_order_trackings</span>
                <span style="color: #6B7280;"> (id_order unique)</span>
            </div>
            <div>
                <span style="color: #EC4899;">EmailTemplate</span>
                <span style="color: #6B7280;"> -&gt; </span>
                <span style="color: #8B5CF6;">soft_email_templates</span>
            </div>

            <div style="color: #9CA3AF;" class="mt-4">// Placeholders</div>
            <div>
                <span style="color: #6B7280;">{reference}</span>
                <span style="color: #6B7280;"> </span>
                <span style="color: #6B7280;">{tracking_code}</span>
                <span style="color: #6B7280;"> </span>
                <span style="color: #6B7280;">{order_id}</span>
            </div>
        </div>

        <div class="code-snippet">
            <div style="color: #9CA3AF;">// Checklist (antes de enviar)</div>
            <div>
                <span style="color: #F59E0B;">[ ]</span>
                <span style="color: #8B5CF6;">Email</span>
                <span style="color: #6B7280;">=&gt;</span>
                <span style="color: #6B7280;">$record-&gt;customer-&gt;email</span>
                <span style="color: #6B7280;">||</span>
                <span style="color: #6B7280;">$record-&gt;email</span>
            </div>
            <div>
                <span style="color: #F59E0B;">[ ]</span>
                <span style="color: #8B5CF6;">Plantilla</span>
                <span style="color: #6B7280;">activa</span>
                <span style="color: #6B7280;">(soft_email_templates)</span>
            </div>
            <div>
                <span style="color: #F59E0B;">[ ]</span>
                <span style="color: #8B5CF6;">Idioma</span>
                <span style="color: #6B7280;">=&gt; translation</span>
                <span style="color: #6B7280;">(subject + html_content)</span>
            </div>
            <div>
                <span style="color: #F59E0B;">[ ]</span>
                <span style="color: #8B5CF6;">tracking_code</span>
                <span style="color: #6B7280;">no vacío</span>
            </div>
        </div>

        <div class="code-snippet">
            <div style="color: #9CA3AF;">// DB (writes/reads)</div>
            <div>
                <span style="color: #EC4899;">write</span>
                <span style="color: #6B7280;">:</span>
                <span style="color: #8B5CF6;">soft_order_trackings</span>
                <span style="color: #6B7280;">(unique: id_order)</span>
            </div>
            <div>
                <span style="color: #EC4899;">read</span>
                <span style="color: #6B7280;">:</span>
                <span style="color: #8B5CF6;">soft_email_templates</span>
                <span style="color: #6B7280;">(+langs)</span>
            </div>
            <div>
                <span style="color: #EC4899;">read</span>
                <span style="color: #6B7280;">:</span>
                <span style="color: #8B5CF6;">soft_orders</span>
                <span style="color: #6B7280;">(reference, customer, country)</span>
            </div>
        </div>

        <div class="code-snippet">
            <div style="color: #9CA3AF;">// What gets persisted (updateOrCreate)</div>
            <div>
                <span style="color: #8B5CF6;">reference</span>
                <span style="color: #6B7280;">,</span>
                <span style="color: #8B5CF6;">customer_name</span>
                <span style="color: #6B7280;">,</span>
                <span style="color: #8B5CF6;">country</span>
            </div>
            <div>
                <span style="color: #8B5CF6;">tracking_code</span>
                <span style="color: #6B7280;">,</span>
                <span style="color: #8B5CF6;">status</span>
                <span style="color: #6B7280;">=</span>
                <span style="color: #F59E0B;">"enviado"</span>
                <span style="color: #6B7280;">,</span>
                <span style="color: #8B5CF6;">sent_at</span>
                <span style="color: #6B7280;">=</span>
                <span style="color: #8B5CF6;">now()</span>
            </div>
        </div>

        <div class="code-snippet">
            <div style="color: #9CA3AF;">// Typical failures</div>
            <div>
                <span style="color: #EF4444;">!</span>
                <span style="color: #8B5CF6;">Mail</span>
                <span style="color: #6B7280;">misconfigured</span>
                <span style="color: #6B7280;">(SMTP)</span>
            </div>
            <div>
                <span style="color: #EF4444;">!</span>
                <span style="color: #8B5CF6;">Template translation</span>
                <span style="color: #6B7280;">missing</span>
                <span style="color: #6B7280;">(fallback subject/html)</span>
            </div>
            <div>
                <span style="color: #EF4444;">!</span>
                <span style="color: #8B5CF6;">Customer email</span>
                <span style="color: #6B7280;">missing</span>
            </div>
        </div>

        <div class="code-snippet">
            <div style="color: #9CA3AF;">// UI note</div>
            <div>
                <span style="color: #6B7280;">Tracking column reads:</span>
            </div>
            <div>
                <span style="color: #8B5CF6;">OrderTracking::where('id_order', ...)-&gt;value('tracking_code')</span>
            </div>
        </div>
    </div>
</div>
