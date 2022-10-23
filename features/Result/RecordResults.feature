Feature: It can create Events

  Scenario: It records the results
    Given the eventStep "Dutch Grand Prix 2022 Race" exists
    And the racer for "Verstappen At Red Bull Racing In 2022" exists
    And the racer for "Perez At Red Bull Racing In 2022" exists
    When a client records the following results for eventStep "Dutch Grand Prix 2022 Race":
      | racer                                 | fastestLapTime | position | points |
      | Verstappen At Red Bull Racing In 2022 | 1'13.652       | 0        | 26     |
      | Perez At Red Bull Racing In 2022      | 1'14.404       | 4        | 10     |
    Then the results are recorded

  Scenario: It cannot record two racers at the same position
    Given the eventStep "Dutch Grand Prix 2022 Race" exists
    And the racer for "Verstappen At Red Bull Racing In 2022" exists
    And the racer for "Perez At Red Bull Racing In 2022" exists
    When a client records the following results for eventStep "Dutch Grand Prix 2022 Race":
      | racer                                 | fastestLapTime | position | points |
      | Verstappen At Red Bull Racing In 2022 | 1'13.652       | 0        | 26     |
      | Perez At Red Bull Racing In 2022      | 1'14.404       | 0        | 26     |
    Then the results are not recorded

  Scenario: It cannot record two results for one racer
    Given the eventStep "Dutch Grand Prix 2022 Race" exists
    And the racer for "Verstappen At Red Bull Racing In 2022" exists
    When a client records the following results for eventStep "Dutch Grand Prix 2022 Race":
      | racer                                 | fastestLapTime | position | points |
      | Verstappen At Red Bull Racing In 2022 | 1'13.652       | 0        | 26     |
      | Verstappen At Red Bull Racing In 2022 | 1'14.404       | 4        | 26     |
    Then the results are not recorded
