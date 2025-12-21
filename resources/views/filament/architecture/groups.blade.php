<div class="space-y-4">
    <div class="text-sm text-gray-700">
        Esta pantalla gestiona los <span class="font-medium">grupos</span> (nombre ES vía langEs y % de descuento).
    </div>

    <div class="max-h-[60vh] overflow-auto pr-1 space-y-4">
        <div class="code-snippet">
            <div style="color: #9CA3AF;">// Filament Resource</div>
            <div>
                <span style="color: #EC4899;">Resource</span>
                <span style="color: #6B7280;">:</span>
                <span style="color: #8B5CF6;">GroupResource</span>
            </div>

            <div style="color: #9CA3AF;" class="mt-4">// Route</div>
            <div>
                <span style="color: #F59E0B;">admin/groups</span>
            </div>

            <div style="color: #9CA3AF;" class="mt-4">// Data Flow</div>
            <div>
                <span style="color: #8B5CF6;">Group</span>
                <span style="color: #6B7280;"> (soft_group)</span>
            </div>
            <div>
                <span style="color: #EC4899;">GroupLang</span>
                <span style="color: #6B7280;"> -&gt; </span>
                <span style="color: #8B5CF6;">langEs</span>
                <span style="color: #6B7280;"> (id_lang=3)</span>
            </div>
            <div>
                <span style="color: #EC4899;">GroupReduction</span>
                <span style="color: #6B7280;"> -&gt; </span>
                <span style="color: #8B5CF6;">id_category=0</span>
            </div>
        </div>

        <div class="code-snippet">
            <div style="color: #9CA3AF;">// Persistencia</div>
            <div>
                <span style="color: #F59E0B;">CreateGroup::afterCreate()</span>
            </div>
            <div>
                <span style="color: #F59E0B;">EditGroup::afterSave()</span>
            </div>
            <div>
                <span style="color: #6B7280;">updateOrCreate(langEs) + updateOrCreate(group_reduction)</span>
            </div>
        </div>
    </div>
</div>
