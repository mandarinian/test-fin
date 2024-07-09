Notes on solution:
1. Application is using PHP 8.3
2. I've discovered, that provided exchange rate provider "https://api.exchangeratesapi.io/latest/" requires paid API
KEY, so I added additional possibility to use free one, by changing `app_new.php:24` to `$commissionCalculator =
$commissionCalculatorFactory->createCommissionCalculator(ExchangeRateProviderEnum::FREE);`
3. I've discovered, that provided binlist provider "https://lookup.binlist.net" has very limited rate limiter, so I
added caching decorator to have possibility to run code repeatedly multiple times.
4. To run code you can:
   * `./vendor/bin/phpunit tests` - run tests
   * `php app.php input.txt` - run old example
   * `php app_new.php input.txt` - run new solution with console output
