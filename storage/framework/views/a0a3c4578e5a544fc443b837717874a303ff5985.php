<html>

<body style="background-color:#e2e1e0;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
  <table style="max-width:670px;margin:50px auto 10px;background-color:#fff;padding:50px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border-top: solid 10px green;">
    <thead>
      <tr>
      <th style="text-align:left;width:50%"><img style="max-width: 150px;" src="https://udaipurmed.in/images/logo.png" alt="UdaipurMed"></th>
        <th style="text-align:right;font-weight:400;width:50%;"><?php echo e(date('Y-m-d H:i:s')); ?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="height:35px;"></td>
      </tr>
      <tr>
        <td colspan="2" style="border: solid 1px #ddd; padding:10px 20px;">
          <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:150px">Order status</span><b style="color:green;font-weight:normal;margin:0">Success</b></p>
          <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Transaction ID</span> <?php echo e($booking_id); ?></p>
          <p style="font-size:14px;margin:0 0 0 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Paid amount</span> Rs. <?php echo e($payment); ?></p>
          <p style="font-size:14px;margin:0 0 0 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Payment ID</span> <?php echo e($pay_id); ?></p>
        </td>
      </tr>
      <tr>
        <td style="height:35px;"></td>
      </tr>
      <tr>
        <td style="width:100%;padding:20px;vertical-align:top">
          <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px">Name</span> <?php echo e($customer_name); ?> </p>
          <!-- <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">Email</span> palash@gmail.com </p> -->
        </td>
       
      </tr>
      <tr>
        <td colspan="2" style="font-size:20px;padding:30px 15px 0 15px;">Detail(s)</td>
      </tr>
      <tr>
        <td colspan="2" style="padding:15px;">
        
          <p style="font-size:14px;margin:0;padding:10px;border:solid 1px #ddd;font-weight:bold;">
            <span style="display:block;font-size:13px;font-weight:normal;">Lab Test Booking</span> Rs. <?php echo e($payment); ?>

          </p>
        </td>
      </tr>
    </tbody>
    <tfooter>
      <tr>
        <td colspan="2" style="font-size:14px;padding:50px 15px 0 15px;">
          <strong style="display:block;margin:0 0 10px 0;">Regards</strong>UdaipurMed<br>
          GST - 08AACCU8564CIZ7<br>
          Udaipur, Rajasthan, India<br><br>
          <b>Phone:</b> 7877264170<br>
          <b>Email:</b> info@udaipurmed.com
        </td>
      </tr>
    </tfooter>
  </table>
</body>

</html><?php /**PATH E:\xampp\htdocs\medicaladmin\resources\views/email/labbooking.blade.php ENDPATH**/ ?>