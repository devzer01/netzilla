This is a reference implementation of SOPG web service clients. There are separate clients for different kinds of users:
- SOPGClassicMerchantClient - supports all classic merchant functionallity with following methods
  - createDisposition
  - assignCardToDisposition
  - assignCardsToDisposition
  - modifyDispositionValue
  - getSerialNumbers
  - getDispositionRawState
  - executeDebit
  - getMid

- SOPGCustomMerchantClient - supports all custom merchant functionallity with following methods
  - executePayment
  - executePaymentACK
  - cancelPayment

- SOPGDistributorClient - supports all distributor funcionallity with following methods
  - activateCard
  - deactivateCard
  - getBalance
  - getCardInfo

- SOPGHappySchenkcardDistributorClient - supports all HappySchenkcard distributor funcionallity with following methods
  - openLoopGiftcardActivation
  - openLoopGiftcardReversal

All methods contain basic verification of required parameters and do not execute a web service call if the required parameters are not valid (for detailed information refer to in code comments).

To run the code PHP version 5.3.6 is required.

The test/src folder provides working examples of client calls.
