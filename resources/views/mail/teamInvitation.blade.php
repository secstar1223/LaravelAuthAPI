<html>
    <div
    style="
        background-color: #f3f3f5;
        padding: 100px 30px;
        font-family: 'system-ui';
    "
    >
    <h1 style="color: #7367f0">Bookings247</h1>
    <div
        style="
        max-width: 500px;
        padding: 20px;
        background-color: white;
        border-radius: 10px;
        "
    >
        <!-- <h3>Dear <strong> </strong>!</h3> -->

        <p>
        You have been invited to join the {{$team}} 's team!
        </p>
        @if($acceptUrl==null)
        <p>If you do not have an account, you may create one by clicking the button below. After creating an account, you may click the invitation acceptance button in this email to accept the team invitation</p>

        <div style="width: 100%; display: flex; justify-content: center">
        <a
            type="button"
            href="{{$registerUrl}}"
            style="
            color: white;
            font-weight: bold;
            background-color: #7367f0;
            font-size: 24px;
            border: none;
            border-radius: 6px;
            margin-bottom: 20px;
            padding: 10px 30px;
            text-decoration: none;
            "
        >
            Create Account
        </a>
        </div>
        @else
        <p>

        If you already have an account, you may accept this invitation by clicking the button below:
        You may accept this invitation by clicking the button below:
        </p>
            <div style="width: 100%; display: flex; justify-content: center">
                <a
                    type="button"
                    href="{{$acceptUrl}}"
                    style="
                    color: white;
                    font-weight: bold;
                    background-color: #7367f0;
                    font-size: 24px;
                    border: none;
                    border-radius: 6px;
                    margin-bottom: 20px;
                    padding: 10px 30px;
                    text-decoration: none;
                    "
                >
                    Accept Invitation
                </a>
            </div>
        @endif
        <p>
        If you have any questions or concerns, please don't hesitate to reach out
        to our customer support team at <strong>support@bookings247.co</strong>.
        </p>

        <p>
        Thank you again for joining us. We look forward to connecting with you
        soon!
        </p>

        <p>Best regards,</p>
        <h4><strong>BOOKINGS247</strong></h4>
    </div>
    </div>
</html>
