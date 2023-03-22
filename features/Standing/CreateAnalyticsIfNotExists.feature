Feature: It can create Drivers

  Scenario: It saves a new driver
    Given the driver "Max Verstappen" exists
    And the season "Formula One 2022" exists
    When a client creates the analytics for "Max Verstappen" during "Formula One 2022"
    | position             | 3     |
    | points               | 415.2 |
    | avgFinishPosition    | 2.71  |
    | classWins            | 5     |
    | fastestLaps          | 7     |
    | finalAppearances     | 3     |
    | hatTricks            | 2     |
    | podiums              | 9     |
    | poles                | 12    |
    | racesLed             | 14    |
    | ralliesLed           | 2     |
    | retirements          | 1     |
    | semiFinalAppearances | 5     |
    | stageWins            | 8     |
    | starts               | 22    |
    | top10s               | 18    |
    | top5s                | 16    |
    | wins                 | 11    |
    | winsPercentage       | 50.0  |
    Then the analytics are saved
    And the id of the analytics for "Max Verstappen" during "Formula One 2022" is returned

  Scenario: It tries to create an existing driver
    Given the analytics for "Max Verstappen 2022" exist
    When a client creates the analytics for the same driver and season
      | position             | 3     |
      | points               | 415.2 |
      | avgFinishPosition    | 2.71  |
      | classWins            | 5     |
      | fastestLaps          | 7     |
      | finalAppearances     | 3     |
      | hatTricks            | 2     |
      | podiums              | 9     |
      | poles                | 12    |
      | racesLed             | 14    |
      | ralliesLed           | 2     |
      | retirements          | 1     |
      | semiFinalAppearances | 5     |
      | stageWins            | 8     |
      | starts               | 22    |
      | top10s               | 18    |
      | top5s                | 16    |
      | wins                 | 11    |
      | winsPercentage       | 50.0  |
    Then the analytics are not duplicated
    And the id of the analytics for "Max Verstappen" during "Formula One 2022" is returned
