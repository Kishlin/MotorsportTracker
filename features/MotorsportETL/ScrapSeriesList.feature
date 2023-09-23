Feature: Scrap series from an external data source

  Scenario: It scrapes series from an external data source
    Given the external data source has the list of series
      | name                               | shortName            | shortCode | category      | uuid                                 |
      | FIA Formula One World Championship | Formula 1            | F1        | Single Seater | a33f8b4a-2b22-41ce-8e7d-0aea08f0e176 |
      | FIA World Endurance Championship   | World Endurance Cham | WEC       | Sportscar     | 967cd5ab-5562-40dc-a0b0-109738adcd01 |
    When I scrap the list of series
    Then the external data source is called "1" times
    And the series list from the external source is cached to minimize future calls
    And it saved "2" new "series"
    And it saved the series "FIA Formula One World Championship"
    And it saved the series "FIA World Endurance Championship"

  Scenario: It scrapes series when a response is cached
    Given it has cached the response for series from the data source
      | name                               | shortName            | shortCode | category      | uuid                                 |
      | FIA Formula One World Championship | Formula 1            | F1        | Single Seater | a33f8b4a-2b22-41ce-8e7d-0aea08f0e176 |
      | FIA World Endurance Championship   | World Endurance Cham | WEC       | Sportscar     | 967cd5ab-5562-40dc-a0b0-109738adcd01 |
    When I scrap the list of series
    Then the external data source is called "0" times
    And it saved "2" new "series"
    And it saved the series "FIA Formula One World Championship"
    And it saved the series "FIA World Endurance Championship"

  Scenario: It skips already existing series
    Given the series exists
        | name                               | short_name | short_code | ref                                  |
        | FIA Formula One World Championship | Formula 1  | F1         | a33f8b4a-2b22-41ce-8e7d-0aea08f0e176 |
    And the external data source has the list of series
      | name                               | shortName            | shortCode | category      | uuid                                 |
      | FIA Formula One World Championship | Formula 1            | F1        | Single Seater | a33f8b4a-2b22-41ce-8e7d-0aea08f0e176 |
      | FIA World Endurance Championship   | World Endurance Cham | WEC       | Sportscar     | 967cd5ab-5562-40dc-a0b0-109738adcd01 |
    When I scrap the list of series
    Then the external data source is called "1" times
    And it saved "1" new "series"
    And it saved the series "FIA World Endurance Championship"

  Scenario: It invalidates cache when required
    Given the external data source has the list of series
      | name                               | shortName            | shortCode | category      | uuid                                 |
      | FIA Formula One World Championship | Formula 1            | F1        | Single Seater | a33f8b4a-2b22-41ce-8e7d-0aea08f0e176 |
      | FIA World Endurance Championship   | World Endurance Cham | WEC       | Sportscar     | 967cd5ab-5562-40dc-a0b0-109738adcd01 |
    Given it has cached the response for series from the data source
    When I scrap the list of series, asking for the cache to be invalidated
    Then the cached response for series is cleared before requesting the external data source
    And the external data source is called "1" times
    And it saved "2" new "series"
    And it saved the series "FIA Formula One World Championship"
    And it saved the series "FIA World Endurance Championship"
