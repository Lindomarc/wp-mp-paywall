<script>
    <?php if (!empty($cookie_content)) : ?>
        Cookies.set('<?php echo PDI_PAYWALL_COOKIE; ?>', '<?php echo json_encode($cookie_content); ?>', {
            expires: 1
        })
    <?php endif; ?>
</script>