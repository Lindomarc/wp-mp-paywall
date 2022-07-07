<label for="pdi_paywall_visibility">Visível somente para:</label>
<select name="pdi_paywall_visibility" id="pdi_paywall_visibility">
    <option value="">Sem restrição</option>
    <option value="<?php echo PDI_PAYWALL_VISIBILITY_REGISTERED ?>" <?php selected($value, PDI_PAYWALL_VISIBILITY_REGISTERED); ?>>Registrado</option>
    <option value="<?php echo PDI_PAYWALL_VISIBILITY_SUBSCRIBER ?>" <?php selected($value, PDI_PAYWALL_VISIBILITY_SUBSCRIBER); ?>>Assinante</option>
    <option value="<?php echo PDI_PAYWALL_VISIBILITY_EXCLUSIVE ?>" <?php selected($value, PDI_PAYWALL_VISIBILITY_EXCLUSIVE); ?>>Exclusivo</option>
</select>
