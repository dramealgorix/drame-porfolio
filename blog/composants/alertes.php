<?php if (!empty($messageErreur)) : ?>
    <div class="alerte alerte-erreur">
        <?= echapper($messageErreur) ?>
    </div>
<?php endif; ?>

<?php if (!empty($messageSucces)) : ?>
    <div class="alerte alerte-succes">
        <?= echapper($messageSucces) ?>
    </div>
<?php endif; ?>