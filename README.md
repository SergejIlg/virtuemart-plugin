# VirtueMart CoinGate Plugin

#### ***Please note that we do not serve U.S. registered businesses due to regulatory reasons yet.***

Accept cryptocurrency payments in your VirtueMart store with [CoinGate](https://coingate.com/) - our fully automated payment processing and invoice system makes it easy, convenient, and risk-free for you and your customers.

With a simple installation of the CoinGate VirtueMart extension in your store's checkout, customers can pay for your goods and services with cryptocurrencies like Bitcoin, Litecoin, Ethereum, Bitcoin Cash, and XRP, among 50+ other altcoins.

With CoinGate’s VirtueMart plugin, merchants can also receive real-time settlements of cryptocurrencies in Euros - payouts are made directly to your bank account. This way, businesses are not exposed to price volatility risk and can enjoy fixed payouts no matter the cryptocurrency’s price.

Alternatively, store owners can choose to receive payouts in cryptocurrency as well.

### Features:
* The gateway is fully automatic – set it and forget it.
* Receive automatic payment confirmations and order status updates.
* Set your prices in any local fiat currency, and the payment amount in cryptocurrency will be calculated using real-time exchange rates.
* [Customize the invoice](https://blog.coingate.com/2019/03/how-to-customize-merchants-invoice-guide/) – disable/enable cryptocurrencies, change their position on the invoice, and more.
* Select the [settlement currencies and payout options](https://blog.coingate.com/2019/08/payouts-fiat-settlements/) for each crypto-asset;
* Use a [sandbox environment](https://sandbox.coingate.com) for testing with Testnet Bitcoin.
* No setup or recurring fees.
* No chargebacks – guaranteed!

### Functionality:
* [Extend invoice expiration time](https://blog.coingate.com/2017/09/bitcoin-merchant-extend-invoice-expiration-time/) up to 24 hours (if payouts are in BTC).
* Accept slight underpayments automatically.
* Refunds can be issued directly from the invoice and without the involvement of the seller.

### How it works - an example:
1. An item in the store costs 100 euro.
2. A customer wants to buy the item and selects to pay with Bitcoin.
3. An invoice is generated and, according to the current exchange rate, the price is 10000 euro per bitcoin, so the customer has to pay 0.01 bitcoins.
4. Once the invoice is paid, the merchant receives 99 euro (100 euro minus our 1% flat fee), or 0.0099 BTC.

*If you’re having trouble installing the plugin, check our blog for a more in-depth description at https://blog.coingate.com/2017/07/virtuemart-bitcoin-extension/*

To use a plugin, a business is required to [pass the verification](https://blog.coingate.com/2019/05/verify-merchant-account-faq/) or [apply for a trial account](https://blog.coingate.com/2020/06/business-trial-account/) first.

Any questions? Write to our support team at [support@coingate.com](mailto:support@coingate.com)

## Installation

Sign up for a CoinGate account at [https://coingate.com](https://coingate.com) for production and [https://sandbox.coingate.com](https://sandbox.coingate.com) for testing (sandbox) environment.

Please note that for "Test" mode you must generate separate API credentials on [https://sandbox.coingate.com](https://sandbox.coingate.com). API credentials generated on [https://coingate.com](https://coingate.com) will not work for "Test" mode.

#### via Extension Manager

1. Download [coingate-virtuemart-1.0.3.zip](https://github.com/coingate/virtuemart-plugin/releases/download/v1.0.3/coingate-virtuemart-1.0.3.zip)

2. Login to your VirtueMart store admin panel, go to *Extensions » Manage » Install*. In the *Upload Package File* part, choose coingate-virtuemart.zip you previously downloaded, then click Upload & Install.

3. Go to *Extensions » Plugins*. In the search box type CoinGate and click Search. Either click on the status indicator located in the CoinGate extension row, or mark the checkbox of CoinGate extension row and click Enable at the top of the admin panel.

4. Go to *VirtueMart » Payment Methods » New*. Type in the information, selecting VM Payment - CoinGate as Payment Method. Be sure to select Yes in the publish section. Click Save. Click Configuration. Fill in your *API Authentication Token* ([https://support.coingate.com/en/42/how-can-i-create-coingate-api-credentials](https://support.coingate.com/en/42/how-can-i-create-coingate-api-credentials)) from CoinGate, choose CoinGate Environment depending on where you created your API credentials([https://coingate.com](https://coingate.com) - Live and [https://sandbox.coingate.com](https://sandbox.coingate.com) - Sandbox) and pick your desired *payment statuses*. 

If you wish to receive bitcoins, set the Receive Currency to *Bitcoin*. If you wish to receive Litecoin, Ethereum, Euros, U.S. Dollars, set the Receive Currency to LTC ETH EUR or USD.

You can also choose the *Do not convert* option to keep *BTC, ETH, LTC* and *BCH* payments received (other Altcoin payments will be converted to BTC). With *Do Not Convert*, you can also extend invoice expiration time up to 24 hours. Be sure to set order statuses correctly. Click Save & Close.
