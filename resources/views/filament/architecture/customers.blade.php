<div class="space-y-4">
    <div class="text-sm text-gray-700">
        Esta pantalla gestiona los <span class="font-medium">clientes</span> y su pertenencia a grupos (grupo por defecto + grupos extra).
    </div>

    <div class="max-h-[60vh] overflow-auto pr-1 space-y-4">
        <div class="code-snippet">
            <div style="color: #9CA3AF;">// Filament Resource</div>
            <div>
                <span style="color: #EC4899;">Resource</span>
                <span style="color: #6B7280;">:</span>
                <span style="color: #8B5CF6;">CustomerResource</span>
            </div>

            <div style="color: #9CA3AF;" class="mt-4">// Routes</div>
            <div>
                <span style="color: #F59E0B;">admin/customers</span>
            </div>
            <div>
                <span style="color: #F59E0B;">admin/customers/create</span>
            </div>

            <div style="color: #9CA3AF;" class="mt-4">// Data Flow</div>
            <div>
                <span style="color: #8B5CF6;">Customer</span>
                <span style="color: #6B7280;"> (soft_customer)</span>
                <span style="color: #6B7280;"> -&gt; </span>
                <span style="color: #8B5CF6;">CustomerForm</span>
            </div>
            <div>
                <span style="color: #EC4899;">default group</span>
                <span style="color: #6B7280;">:</span>
                <span style="color: #8B5CF6;">id_default_group</span>
            </div>
            <div>
                <span style="color: #EC4899;">groups pivot</span>
                <span style="color: #6B7280;">:</span>
                <span style="color: #8B5CF6;">customer-&gt;groups()</span>
            </div>
        </div>

        <div class="code-snippet">
            <div style="color: #9CA3AF;">// Persistencia de grupos</div>
            <div>
                <span style="color: #F59E0B;">CreateCustomer::afterCreate()</span>
            </div>
            <div>
                <span style="color: #F59E0B;">EditCustomer::afterSave()</span>
            </div>
            <div>
                <span style="color: #6B7280;">sync(group_ids) + asegurar id_default_group</span>
            </div>
        </div>

        <div class="code-snippet">
            <div style="color: #9CA3AF;">// Acciones</div>
            <div>
                <span style="color: #8B5CF6;">Edit</span>
                <span style="color: #6B7280;">=&gt;</span>
                <span style="color: #6B7280;">slideOver</span>
            </div>
            <div>
                <span style="color: #8B5CF6;">Approve BTB</span>
                <span style="color: #6B7280;">=&gt;</span>
                <span style="color: #6B7280;">asigna grupo BTB (+ opcional crea grupo descuento)</span>
            </div>
        </div>
    </div>
</div>
