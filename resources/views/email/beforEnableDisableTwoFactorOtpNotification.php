<h1>Two-Factor Authentication
    <?= $activeStatus == 1 ? 'Deactivation' : 'Activation' ?>
</h1>
Use the below OTP to
<?= $activeStatus == 1 ? 'Deactivation' : 'Activation' ?> your two-factor authentication
<br>
<b>
    <?php echo $otp;?>
     
</b>