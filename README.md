# A simple and free accounting program
N4S is a free and open source accounting system, and thus free to use now and forever.

You can easily build in custom logic to optimize and refine your processes. 

# Supports any currency 
Find your transactions easily based on the currency amount, not Your base currency... !
Is available when You import currency code and currency amount on Your transactions.

# Features
* Automatic projection of accounts (forecasting) infinite number of months, so far the future monthly balances are calculated as an average of previous months, so is only useful for stable ongoing operations without seasonal fluctuations, e.g. service companies that sell all year round, or shops with stable turnover (Select budget in menu)
* Unlimited amount of currencies / commodities
* Advanced extended file structure and versioning and tracking on data/transactions.
* Automatic Periodization when specifying the period for transactions
* Automatic depreciation when specifying the lifetime of transactions
* VAT codes (i,u,iv-eu,iy-eu,iv-abr,iy-abr,rep)
* No need to close the book at the end of the year, accounting periods are dynamic and are created automatically. You can report across any period in time, no matter what Your accounting Year is.
* Report generator and transaction explorer (web application and text-based)
* Import of transactions from CSV or live bank connection - anyone can connect their bank for free using the Gocardless Service, which is free to use for small businesses 😍
* Export of transactions to XML and CSV
* Automatic calculation of interest where you can specify the interest rate on a given account
* Automatic periodization where you can specify a start and end date for each expense
* Automatic consolidation
* Account reconciliation / Bank Reconciliation - compare any account with the corresponding statement (CSV) and display disrepancies
* Logic / rules – option to define rules for automatic accounting of postings
* Search for transactions and mass update based on specified criteria
* Presentable reporting (balances and account cards)
* Option to generate links for a given accounting period for an account that can be opened without login, where you can see the balance and browse the underlying postings.
* Tax accounting – manual
* Document handling system drag & drop to the terminal.
* Tool for efficient creation of transactions
* Loading of OIOUBL (XML) invoices
* Simple Automatic consolidation of group accounts
* Unique way to document the correctness of the accounts. Posted transactions are posted on the blockchain where the next posted transaction verifies all previously posted transactions by including their total md5 hash. Thus, it is not possible to fiddle with posted entries without the book becoming invalid. We recommend that you regularly sign your accounting hash to document the transactions.
* Full log of all changes. Every change to transactions is logged on the transaction with operator, change, and date and time
* Full log of all actions, output from all displayed balances, account cards, etc. with date and time stamp for each user for each account, entry of transactions, etc. - searchable, e.g. useful if you need to trace an amount in the accounts that no longer exists, entering transactions, etc. - searchable, e.g. useful if you need to trace an amount in the accounts that no longer exists
* An infinite number of options for different color schemes - there are, for example, several color schemes that are suitable for you to sit in the sun with your laptop and work - you can forget all about that in other systems... !
* Mapping to the Danish Business Authority's standard chart of accounts for external reporting, including easy entry into Accounting 2.0

# Running updates - rolling release
You don't have to wait years for a new version to be released. You can continuously download patches with improvements to the system.
Most recent updates, You can also view the [Changelog](Changelog.md) or browse [All commits](https://github.com/n4s-linux/n4s-gratis-regnskab/commits/main/)
It takes under a minute to update the n4s to the latest version.
This is how you update the system
> upd8

## Recent updates
| at         | hash         | desc |
|------------|--------------|------|
| 2024-05-20 | [afd42d28...](https://github.com/n4s-linux/n4s-your-second-accounting-brain/commit/afd42d28b4a91d3a40b6f46ff460a1e1f04e48aa) | 💡 New Feature: View all transactions when entering a transaction that is an opening |
| 2024-05-20 | [40375276...](https://github.com/n4s-linux/n4s-your-second-accounting-brain/commit/4037527625dafa55af081dc144db2363875a4f84) | 🐛 Fault tolerance some key numbers will be missing in some accounts |
| 2024-05-18 | [9018a220...](https://github.com/n4s-linux/n4s-your-second-accounting-brain/commit/9018a2203b2b207c73aeecbc2a663ce57708a4e4) | 💡 New Feature: Guide that allows to book specific or all transactions, and verify the book later on to see if any of the booked transactions has changed #blockchain |
| 2024-05-18 | [719bd342...](https://github.com/n4s-linux/n4s-your-second-accounting-brain/commit/719bd34233245848122d5f0767a47c5a0d369ae6) | 💡New Feature: Nicer balance with integration to register |
| 2024-06-05 | [c9d4eadc...](https://github.com/n4s-linux/n4s-your-second-accounting-brain/commit/c9d4eadc3ae51ed01b39614053b68ca7276f6292) | 💡 New Feature: Pick account from similar transactions in the book |
| 2024-06-14 | [573d8cac...](https://github.com/n4s-linux/n4s-your-second-accounting-brain/commit/573d8cacfff6960a879cff4476bfb0fa4da0ae7a) | 💡 New Feature: Filter transactions based on any text |
| 2024-06-19 | [a42d2374...](https://github.com/n4s-linux/n4s-your-second-accounting-brain/commit/a42d2374722228744ff46e49c64f320e812431fc) | 🐛 Bugfix: Fault tolerance if no totals |
| 2024-06-19 | [ca23d700...](https://github.com/n4s-linux/n4s-your-second-accounting-brain/commit/ca23d700adeff1b323e4068f131480dc4f1274e5) | 💡 New feature - filter transactions |
| 2024-06-19 | [bbf63510...](https://github.com/n4s-linux/n4s-your-second-accounting-brain/commit/bbf63510f827a523462326556e0eda72663bf1ce) | 💡 New Feature - Preview of consequences before loading CSV data - approve or not |
| 2024-06-23 | [93af18a2...](https://github.com/n4s-linux/n4s-your-second-accounting-brain/commit/93af18a260990556536bf040f5703de452d7858c) | 💡 New Feature: CSV importer remembers Your preferences and has a better preview including account aliases |
| 2024-06-23 | [a4560ff8...](https://github.com/n4s-linux/n4s-your-second-accounting-brain/commit/a4560ff8122c1467f9a600bf6f5164fd2eb5d0a5) | 💡 New Feature: Bilags ssh viewer, and get bilags structured data via Taggun API |
| 2024-05-29 | [5f9c9937...](https://github.com/n4s-linux/n4s-your-second-accounting-brain/commit/5f9c9937ceb3621e3b52e141103a9ee5f078eac6) | 💡 New Feature - Smart bank integration via gocardless - which is free for small businesses 💚💚💚 |
| 2024-05-28 | [bbbad819...](https://github.com/n4s-linux/n4s-your-second-accounting-brain/commit/bbbad819a80564828d8152b16d2114c2130b8a11) | 💡 New Feature: Manual accounts support debitor/creditor |
| 2024-05-28 | [0a2ba6aa...](https://github.com/n4s-linux/n4s-your-second-accounting-brain/commit/0a2ba6aaf531ab1f35a33742c78f16185c0d56c3) | 💡 New feature - report with/without primo carryover amounts |
| 2024-06-24 | [92fdaa42...](https://github.com/n4s-linux/n4s-your-second-accounting-brain/commit/92fdaa422d2447454690f27e8b00a6e31093f6b1) | 💡 New Feature: eeping archived version of edited transactions for future reference |
| 2024-07-02 | [94243d66...](https://github.com/n4s-linux/n4s-your-second-accounting-brain/commit/94243d66ff2f75683ffc023621cd1d60ee0dcc58) | 💡 New Feature: Voucher handling System - Tries and fetch document data via Tagunn API - displays documents in Zathura |
| 2024-07-02 | [ffc24dd0...](https://github.com/n4s-linux/n4s-your-second-accounting-brain/commit/ffc24dd091a66236124cb29b7e943b4a8e5cb6c8) | 💡 New Feature: En (editable) description for each account in the tree |
| 2024-07-07 | [bdb5d083...](https://github.com/n4s-linux/n4s-your-second-accounting-brain/commit/bdb5d083ecaeb6e516396185782de6dff2f88e6d) | 💡 New Feature: List of accounts with unhnandled bank transactions and vouchers |
| 2024-07-07 | [fee62b3f...](https://github.com/n4s-linux/n4s-your-second-accounting-brain/commit/fee62b3f57cc3a5aded550e97abc5aabf95c9a36) | 🐛 Fault tolerance - handle empty accounts |
| 2024-07-07 | [c1019a50...](https://github.com/n4s-linux/n4s-your-second-accounting-brain/commit/c1019a508192024f97bec7b256fd4186dd91f249) | 🐛 Bugfix: Missing references to operator |
| 2024-08-16 | [6c02ecff...](https://github.com/n4s-linux/n4s-your-second-accounting-brain/commit/6c02ecffc5a781ae55e1bd441f7316a7ece2f0d4) | 💡 New Feature: View booked transactions in same window |
| 2024-08-28 | [629912bb...](https://github.com/n4s-linux/n4s-your-second-accounting-brain/commit/629912bb1890dabba6f674a220b03e7a7cb16d0d) | 💡 New Feature: Document preview for already booked documents, and convert png/jpg to pdf for preview |
| 2024-09-09 | [37f1e38a...](https://github.com/n4s-linux/n4s-your-second-accounting-brain/commit/37f1e38a8f405583765b56a4cc238c6e9566d89e) | 💡 New Feature: Banned accounts - also bugfix to avoid duplicate suggestions on files |
| 2024-09-09 | [ec74b891...](https://github.com/n4s-linux/n4s-your-second-accounting-brain/commit/ec74b8917952c0a27aa311491095743874f9c03a) | 🐛 Bugfix: Mkentry quicktransaction buffering problem |
| 2024-09-09 | [dd52d493...](https://github.com/n4s-linux/n4s-your-second-accounting-brain/commit/dd52d4932d7340bfba06db5663482ecdb6adc048) | 🐛 Bugfix: Map suggestiont fault tolerance if no history yet |
| 2024-09-21 | [ceab6adf...](https://github.com/n4s-linux/n4s-your-second-accounting-brain/commit/ceab6adf8906e12d0a4a28b2c0da7807fd8a2c49) | 💡 New feature: Transplanted lines are stamped with transplant date and src for full history |
