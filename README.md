# A simple and free accounting program
N4S is a free and open source accounting system, and thus free to use now and forever.

You can easily build in custom logic to optimize and refine your processes. 

It is basically a whole ERP ecosystem built around ledger/hledger, tmux bash and vim. Its designed to be run through any unicode capable terminal emulator at fast speed, but there will eventually also be a webinterface.

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

