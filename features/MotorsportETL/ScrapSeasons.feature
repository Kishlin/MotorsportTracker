Feature: Scrap seasons from an external data source

  Scenario: It scrapes seasons from an external data source
    Given the series exists
      | name                               | short_name | short_code | ref                                  |
      | FIA Formula One World Championship | Formula 1  | F1         | a33f8b4a-2b22-41ce-8e7d-0aea08f0e176 |
    And the external data source has the list of seasons for "FIA Formula One World Championship"
      | name                    | year | uuid                                 |
      | 2023 World Championship | 2023 | bbe13c43-7cc2-4cca-90a0-9268e98ff6e3 |
      | 2022 World Championship | 2022 | 789a5f6e-ea9d-46c4-9c55-62072c4beaa7 |
      | 2021 World Championship | 2021 | c620fbb8-7169-4f6a-af74-13f6cd80a233 |
    When I scrap the list of seasons for "FIA Formula One World Championship"
    Then the external data source is called "1" times
    And the seasons list for "FIA Formula One World Championship" from the external source is cached to minimize future calls
    And it saved "3" new "season"
    And it saved the seasons for "FIA Formula One World Championship" "2023"
    And it saved the seasons for "FIA Formula One World Championship" "2022"
    And it saved the seasons for "FIA Formula One World Championship" "2021"

  Scenario: It scrapes seasons when a response is cached
    Given the series exists
      | name                               | short_name | short_code | ref                                  |
      | FIA Formula One World Championship | Formula 1  | F1         | a33f8b4a-2b22-41ce-8e7d-0aea08f0e176 |
    And it has cached the response for seasons for "FIA Formula One World Championship" from the data source
      | name                    | year | uuid                                 |
      | 2023 World Championship | 2023 | bbe13c43-7cc2-4cca-90a0-9268e98ff6e3 |
      | 2022 World Championship | 2022 | 789a5f6e-ea9d-46c4-9c55-62072c4beaa7 |
      | 2021 World Championship | 2021 | c620fbb8-7169-4f6a-af74-13f6cd80a233 |
    When I scrap the list of seasons for "FIA Formula One World Championship"
    Then the external data source is called "0" times
    And it saved "3" new "season"
    And it saved the seasons for "FIA Formula One World Championship" "2023"
    And it saved the seasons for "FIA Formula One World Championship" "2022"
    And it saved the seasons for "FIA Formula One World Championship" "2021"

  Scenario: It skips already existing seasons
    Given the series exists
      | name                               | short_name | short_code | ref                                  |
      | FIA Formula One World Championship | Formula 1  | F1         | a33f8b4a-2b22-41ce-8e7d-0aea08f0e176 |
    Given the seasons for "FIA Formula One World Championship" exist
      | year | ref                                  |
      | 2023 | bbe13c43-7cc2-4cca-90a0-9268e98ff6e3 |
      | 2022 | 789a5f6e-ea9d-46c4-9c55-62072c4beaa7 |
    And the external data source has the list of seasons for "FIA Formula One World Championship"
      | name                    | year | uuid                                 |
      | 2023 World Championship | 2023 | bbe13c43-7cc2-4cca-90a0-9268e98ff6e3 |
      | 2022 World Championship | 2022 | 789a5f6e-ea9d-46c4-9c55-62072c4beaa7 |
      | 2021 World Championship | 2021 | c620fbb8-7169-4f6a-af74-13f6cd80a233 |
    When I scrap the list of seasons for "FIA Formula One World Championship"
    Then the external data source is called "1" times
    And it saved "1" new "season"
    And it saved the seasons for "FIA Formula One World Championship" "2021"

  Scenario: It invalidates cache when required
    Given the series exists
      | name                               | short_name | short_code | ref                                  |
      | FIA Formula One World Championship | Formula 1  | F1         | a33f8b4a-2b22-41ce-8e7d-0aea08f0e176 |
    And the external data source has the list of seasons for "FIA Formula One World Championship"
      | name                    | year | uuid                                 |
      | 2023 World Championship | 2023 | bbe13c43-7cc2-4cca-90a0-9268e98ff6e3 |
      | 2022 World Championship | 2022 | 789a5f6e-ea9d-46c4-9c55-62072c4beaa7 |
      | 2021 World Championship | 2021 | c620fbb8-7169-4f6a-af74-13f6cd80a233 |
    Given it has cached the response for seasons for "FIA Formula One World Championship" from the data source
    When I scrap the list of seasons for "FIA Formula One World Championship", asking for the cache to be invalidated
    Then the cached response for seasons for "FIA Formula One World Championship" is cleared before requesting the external data source
    And the external data source is called "1" times
    And it saved "3" new "season"
    And it saved the seasons for "FIA Formula One World Championship" "2023"
    And it saved the seasons for "FIA Formula One World Championship" "2022"
    And it saved the seasons for "FIA Formula One World Championship" "2021"
