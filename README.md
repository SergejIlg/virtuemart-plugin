# VirtueMart CoinGate Plugin

CoinGate bitcoin payment gateway VirtueMart plugin.


## Install

Sign up for CoinGate account at https://coingate.com for production and https://sandbox.coingate.com for testing (sandbox) environment.

### via Extension Manager

1. Download [coingate-virtuemart-1.0.0.zip](https://github.com/coingate/virtuemart-plugin/releases/download/v1.0.0/coingate-virtuemart-1.0.0.zip)

2. Login to your VirtueMart store admin panel, go to *Extensions » Extension Manager » Install*. In the *Upload Package File* part, choose **coingate-virtuemart.zip** you previously downloaded, then click **Upload & Install**.

3. Go to *Extensions » Extension Manager » Manage*.
In search box type **CoinGate** and click **Search**. Either click on status indicator located in CoinGate extension row, or mark the checkbox of CoinGate extension row and click **Enable** at the top of admin panel.

4. Go to *VirtueMart » Payment Methods » New*. Type in the information, selecting **VM Payment - CoinGate** as **Payment Method**. Be sure to select **Yes** in the publish section. Click **Save**. Click **Configuration**. Fill in your [API Credentials](http://support.coingate.com/knowledge_base/topics/how-can-i-create-coingate-api-credentials) (*App ID*, *Api Key*, *Api Secret*) from CoinGate and choose *CoinGate Environment* depending on where you created your API credentials(https://coingate.com - Live and https://sandbox.coingate.com - Sandbox). If you wish to receive bitcoins, set the **Receive Currency** to *Bitcoin*. If you wish to receive Euros or U.S. Dollars, set the **Receive Currency** to *EUR* or *USD*. Please note that if you set the **Receive Currency** to *EUR* or *USD*, we will require you to verify your account (*BTC* does not require verification). **Be sure to set order statuses correctly**. Click **Save & Close**.
