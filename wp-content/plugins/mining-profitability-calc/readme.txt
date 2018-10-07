Using Guzzle - http://guzzle.readthedocs.io/en/latest/overview.html#installation

Add `last_updated_coin_market_cap` to wp_ticker:

    //add column to wp_ticker
    ALTER TABLE wp_ticker
    ADD last_updated_coin_market_cap datetime NOT NULL;

    //remove column from wp_coins
    ALTER TABLE wp_coins
    DROP COLUMN last_updated_coin_market_cap;
