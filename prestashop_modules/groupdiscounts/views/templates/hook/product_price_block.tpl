{if isset($discount_percentage) && $discount_percentage > 0}
    <div class="group-discount-info">
        <span class="discount-badge">
            {l s='Group discount:' mod='groupdiscounts'} {$discount_percentage|round:0}%
        </span>
        {if isset($group_name)}
            <small class="group-name">({$group_name})</small>
        {/if}
    </div>
{/if}
