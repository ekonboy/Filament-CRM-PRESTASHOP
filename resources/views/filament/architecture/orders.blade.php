<div class="space-y-4">
    <div class="text-sm text-gray-700">
        Esta pantalla lista y permite editar <span class="font-medium">pedidos</span> importados desde PrestaShop, con
        acceso a tracking y notas internas.
    </div>

    <div class="max-h-[60vh] overflow-auto pr-1 space-y-4">
        <div class="code-snippet">
            <div style="color: #9CA3AF;">// Filament Resource</div>
            <div>
                <span style="color: #EC4899;">Resource</span>
                <span style="color: #6B7280;">:</span>
                <span style="color: #8B5CF6;">OrderResource</span>
            </div>

            <div style="color: #9CA3AF;" class="mt-4">// Route</div>
            <div>
                <span style="color: #F59E0B;">admin/orders</span>
            </div>

            <div style="color: #9CA3AF;" class="mt-4">// Data</div>
            <div>
                <span style="color: #8B5CF6;">Order</span>
                <span style="color: #6B7280;"> (soft_orders)</span>
            </div>
            <div>
                <span style="color: #8B5CF6;">Customer</span>
                <span style="color: #6B7280;"> (relation / fallback firstname+lastname)</span>
            </div>
            <div>
                <span style="color: #8B5CF6;">DeliveryAddress</span>
                <span style="color: #6B7280;"> -&gt; country.langEs.name</span>
            </div>
        </div>

        <div class="code-snippet">
            <div style="color: #9CA3AF;">// UI (table)</div>
            <div>
                <span style="color: #8B5CF6;">columns</span>
                <span style="color: #6B7280;">:</span>
                <span style="color: #6B7280;"> id_order, reference, customer_name, total_paid, date_add,
                    shipping_country</span>
            </div>
            <div>
                <span style="color: #8B5CF6;">actions</span>
                <span style="color: #6B7280;">:</span>
                <span style="color: #6B7280;"> EditAction</span>
            </div>
        </div>

        <div class="code-snippet">
            <div style="color: #9CA3AF;">// Form (edit/create)</div>
            <div>
                <span style="color: #8B5CF6;">tracking_code</span>
                <span style="color: #6B7280;">,</span>
                <span style="color: #8B5CF6;">details</span>
                <span style="color: #6B7280;">(repeater, readonly)</span>
                <span style="color: #6B7280;">,</span>
                <span style="color: #8B5CF6;">private_note</span>
            </div>
        </div>

        <div class="code-snippet">
            <div style="color: #9CA3AF;">// Notes</div>
            <div>
                <span style="color: #6B7280;">Cliente:</span>
                <span style="color: #8B5CF6;">$record-&gt;customer</span>
                <span style="color: #6B7280;"> o fallback </span>
                <span style="color: #8B5CF6;">firstname/lastname</span>
            </div>
            <div>
                <span style="color: #6B7280;">País envío:</span>
                <span style="color: #8B5CF6;">$record-&gt;deliveryAddress?->country?->langEs?->name</span>
                <span style="color: #6B7280;"> o </span>
                <span style="color: #8B5CF6;">country_delivery</span>
            </div>
        </div>


        <div class="code-snippet">
            <div style="color: #9CA3AF;">// Notas del Pedido</div>
            <div>
                <span style="color: #6B7280;">Nota del Pedido:</span>
                <span style="color: #8B5CF6;">Notas de este pedido...</span>
                <span style="color: #F59E0B;">-></span>
                <span style="color: #6B7280;">soft_orders.id_order</span>

            </div>
            <div>
                <span style="color: #6B7280;">Nota del cliente Privada:</span>
                <span style="color: #8B5CF6;">Notas generales del cliente (todos sus pedidos)</span>
                <span style="color: #F59E0B;">-></span>
                <span style="color: #6B7280;">soft_customer.note </span>
            </div>
        </div>


    </div>
</div>
