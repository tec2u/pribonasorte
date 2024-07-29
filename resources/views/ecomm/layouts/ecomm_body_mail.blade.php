<div style="width: 100%; display: inline-block;">
  <!--  -->
  <div style="width: 90%; margin: 0px 5% 0px 5%; display: inline-block;">
    <!--  -->
    <div
      style="width: 90%; padding: 40px 5% 40px 5%; margin-top: 50px; display: inline-block; border: solid 1px #cdcdcd; border-radius: 20px;">
      <center>
        <!--  -->
        <p style="margin-top: 20px;font-weight: bold; font-size: 20px;">Hello {{ $info_user->name }}!</p>
        <!--  -->

        <p style="margin-top: 20px;">Use the code below to change your password on the Lifeprosper website.</p>
        <div style="width: 90%; padding: 30px 5% 30px 5%; border-radius: 20px; border: solid #cdcdcd 1px;">
          <!--  -->
          <p style="font-weight: bold; color: #333333; font-size: 30px;">{{ $info_user->replice_code }}</p>
        </div>
      </center>
    </div>
  </div>
</div>
