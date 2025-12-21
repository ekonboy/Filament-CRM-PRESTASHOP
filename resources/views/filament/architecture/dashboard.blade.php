<div class="space-y-3 text-sm text-gray-700 mt-4">
    <div>
        Esta pantalla es el punto de entrada del panel de administración.
    </div>

    <div>
        Aquí se muestran accesos rápidos y widgets de control para tareas internas del CRM y utilidades no nativas de PrestaShop.
    </div>

    <div class="code-snippet">
        <div style="color: #9CA3AF;">// Filament Page</div>
        <div>
            <span style="color: #EC4899;">Page</span>
            <span style="color: #6B7280;">:</span>
            <span style="color: #8B5CF6;">Dashboard</span>
        </div>

        <div style="color: #9CA3AF;" class="mt-4">// Route</div>
        <div>
            <span style="color: #F59E0B;">admin</span>
        </div>

        <div style="color: #9CA3AF;" class="mt-4">// Purpose</div>
        <div>
            <span style="color: #8B5CF6;">Widgets</span>
            <span style="color: #6B7280;"> + </span>
            <span style="color: #8B5CF6;">Quick links</span>
            <span style="color: #6B7280;"> =&gt; KPIs y accesos a recursos</span>
        </div>
    </div>

    <div class="code-snippet">
        <div style="color: #9CA3AF;">// Dashboard Widget</div>
        <div>
            <span style="color: #EC4899;">Widget</span>
            <span style="color: #6B7280;">:</span>
            <span style="color: #8B5CF6;">CoreMonitor</span>
        </div>

        <div style="color: #9CA3AF;" class="mt-4">// View</div>
        <div>
            <span style="color: #F59E0B;">filament.widgets.core-monitor</span>
        </div>

        <div style="color: #9CA3AF;" class="mt-4">// Reads (PrestaShop)</div>
        <div>
            <span style="color: #8B5CF6;">soft_webservice_account</span>
            <span style="color: #6B7280;"> (active)</span>
        </div>
        <div>
            <span style="color: #8B5CF6;">soft_shop</span>
            <span style="color: #6B7280;"> (multishop)</span>
        </div>
        <div>
            <span style="color: #8B5CF6;">soft_lang</span>
            <span style="color: #6B7280;"> (active)</span>
        </div>
        <div>
            <span style="color: #8B5CF6;">soft_carrier</span>
            <span style="color: #6B7280;"> (active, deleted=0)</span>
        </div>
        <div>
            <span style="color: #8B5CF6;">soft_module</span>
            <span style="color: #6B7280;"> (payment*, active)</span>
        </div>

        <div style="color: #9CA3AF;" class="mt-4">// Reads (Laravel)</div>
        <div>
            <span style="color: #8B5CF6;">LoginLog</span>
            <span style="color: #6B7280;"> (today)</span>
        </div>

        <div style="color: #9CA3AF;" class="mt-4">// Action</div>
        <div>
            <span style="color: #8B5CF6;">testConnection()</span>
            <span style="color: #6B7280;"> =&gt; DB::table('soft_shop') + Notification</span>
        </div>
    </div>

    <div class="text-gray-500">
        Esta sección se irá ampliando con la arquitectura del sistema y explicaciones por pantalla.
    </div>
</div>
