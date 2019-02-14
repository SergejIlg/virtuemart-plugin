# VirtueMart CoinGate Plugin

Accept Bitcoin & Altcoins on your VirtueMart store.

Read the module installation instructions below to get started with CoinGate Bitcoin & Altcoin payment gateway on your shop.
Full setup guide with screenshots is also available on our blog: <https://blog.coingate.com/2017/07/virtuemart-bitcoin-extension/>


## Install

Sign up for CoinGate account at <https://coingate.com> for production and <https://sandbox.coingate.com> for testing (sandbox) environment.

Please note, that for "Test" mode you **must** generate separate API credentials on <https://sandbox.coingate.com>. API credentials generated on <https://coingate.com> will **not** work for "Test" mode.

### via Extension Manager

1. Download [coingate-virtuemart-1.0.2.zip](https://github.com/coingate/virtuemart-plugin/releases/download/v1.0.2/coingate-virtuemart-1.0.2.zip)

2. Login to your VirtueMart store admin panel, go to *Extensions » Manage » Install*. In the *Upload Package File* part, choose **coingate-virtuemart.zip** you previously downloaded, then click **Upload & Install**.

3. Go to *Extensions » Plugins*. In search box type **CoinGate** and click **Search**. Either click on status indicator located in CoinGate extension row, or mark the checkbox of CoinGate extension row and click **Enable** at the top of admin panel.

4. Go to *VirtueMart » Payment Methods » New*. Type in the information, selecting **VM Payment - CoinGate** as **Payment Method**. Be sure to select **Yes** in the publish section. Click **Save**. Click **Configuration**. Fill in your *API Authentication Token* (https://support.coingate.com/en/42/how-can-i-create-coingate-api-credentials) from CoinGate, choose *CoinGate Environment* depending on where you created your API credentials(https://coingate.com - Live and https://sandbox.coingate.com - Sandbox) and pick your desired *payment statuses*. If you wish to receive bitcoins, set the **Receive Currency** to *Bitcoin*. If you wish to receive Litecoin, Ethereum, Euros, U.S. Dollars, set the **Receive Currency** to **LTC ETH EUR** or **USD**. Please note that if you set the **Receive Currency** to *EUR* or *USD*, we will require you to verify your account (*BTC* does not require verification).You can also choose the *Do not convert* option to keep *BTC, ETH, LTC* and *BCH* payments received (other Altcoin payments will be converted to *BTC*). With *Do Not Convert* you can also extend invoice expiration time up to 24 hours. **Be sure to set order statuses correctly**. Click **Save & Close**.