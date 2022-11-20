Feature: It can create Events

  @backoffice
  Scenario: It records the results
    Given the eventStep "Dutch Grand Prix 2022 Race" exists
    And the racer for "Verstappen At Red Bull Racing In 2022" exists
    And the racer for "Perez At Red Bull Racing In 2022" exists
    When a client records the following results for eventStep "Dutch Grand Prix 2022 Race":
      | racer                                 | position | points |
      | Verstappen At Red Bull Racing In 2022 | 0        | 26     |
      | Perez At Red Bull Racing In 2022      | 4        | 10     |
    Then the results are recorded
    And the driver's standings are now
      | driver          | event                 | points |
      | Max Verstappen  | Dutch Grand Prix 2022 | 26     |
      | Sergio Perez    | Dutch Grand Prix 2022 | 10     |
    And the team's standings are now
      | team            | event                 | points |
      | Red Bull Racing | Dutch Grand Prix 2022 | 36     |

  @backoffice
  Scenario: It records results over multiple events and steps
    Given the eventStep "Australian Grand Prix 2022 Race" exists
    And the racer for "Verstappen At Red Bull Racing In 2022" exists
    And the racer for "Perez At Red Bull Racing In 2022" exists
    And the racer for "Hamilton At Mercedes In 2022" exists
    When a client records the following results for eventStep "Australian Grand Prix 2022 Race":
      | racer                                 | position | points |
      | Perez At Red Bull Racing In 2022      | 2        | 18     |
      | Hamilton At Mercedes In 2022          | 4        | 12     |
      | Verstappen At Red Bull Racing In 2022 | 18       | 0      |
    Then the driver's standings are now
      | driver          | event                      | points |
      | Sergio Perez    | Australian Grand Prix 2022 | 18     |
      | Lewis Hamilton  | Australian Grand Prix 2022 | 12     |
      | Max Verstappen  | Australian Grand Prix 2022 | 0      |
    And the team's standings are now
      | team            | event                      | points |
      | Red Bull Racing | Australian Grand Prix 2022 | 18     |
      | Mercedes        | Australian Grand Prix 2022 | 12     |
    Given the eventStep "Emilia Romagna Grand Prix 2022 Sprint Qualifying" exists
    When a client records the following results for eventStep "Emilia Romagna Grand Prix 2022 Sprint Qualifying":
      | racer                                 | position | points |
      | Perez At Red Bull Racing In 2022      | 3        | 6      |
      | Verstappen At Red Bull Racing In 2022 | 1        | 8      |
      | Hamilton At Mercedes In 2022          | 14       | 0      |
    Then the driver's standings are now
      | driver          | event                          | points |
      | Sergio Perez    | Australian Grand Prix 2022     | 18     |
      | Lewis Hamilton  | Australian Grand Prix 2022     | 12     |
      | Max Verstappen  | Australian Grand Prix 2022     | 0      |
      | Sergio Perez    | Emilia Romagna Grand Prix 2022 | 24     |
      | Lewis Hamilton  | Emilia Romagna Grand Prix 2022 | 12     |
      | Max Verstappen  | Emilia Romagna Grand Prix 2022 | 8      |
    And the team's standings are now
      | team            | event                          | points |
      | Red Bull Racing | Australian Grand Prix 2022     | 18     |
      | Mercedes        | Australian Grand Prix 2022     | 12     |
      | Red Bull Racing | Emilia Romagna Grand Prix 2022 | 32     |
      | Mercedes        | Emilia Romagna Grand Prix 2022 | 12     |
    Given the eventStep "Emilia Romagna Grand Prix 2022 Race" exists
    When a client records the following results for eventStep "Emilia Romagna Grand Prix 2022 Race":
      | racer                                 | position | points |
      | Verstappen At Red Bull Racing In 2022 | 1        | 26     |
      | Perez At Red Bull Racing In 2022      | 2        | 18     |
      | Hamilton At Mercedes In 2022          | 13       | 0      |
    Then the driver's standings are now
      | driver          | event                          | points |
      | Sergio Perez    | Australian Grand Prix 2022     | 18     |
      | Lewis Hamilton  | Australian Grand Prix 2022     | 12     |
      | Max Verstappen  | Australian Grand Prix 2022     | 0      |
      | Sergio Perez    | Emilia Romagna Grand Prix 2022 | 42     |
      | Lewis Hamilton  | Emilia Romagna Grand Prix 2022 | 12     |
      | Max Verstappen  | Emilia Romagna Grand Prix 2022 | 34     |
    And the team's standings are now
      | team            | event                          | points |
      | Red Bull Racing | Australian Grand Prix 2022     | 18     |
      | Mercedes        | Australian Grand Prix 2022     | 12     |
      | Red Bull Racing | Emilia Romagna Grand Prix 2022 | 76     |
      | Mercedes        | Emilia Romagna Grand Prix 2022 | 12     |

  Scenario: It cannot record two racers at the same position
    Given the eventStep "Dutch Grand Prix 2022 Race" exists
    And the racer for "Verstappen At Red Bull Racing In 2022" exists
    And the racer for "Perez At Red Bull Racing In 2022" exists
    When a client records the following results for eventStep "Dutch Grand Prix 2022 Race":
      | racer                                 | position | points |
      | Verstappen At Red Bull Racing In 2022 | 0        | 26     |
      | Perez At Red Bull Racing In 2022      | 0        | 26     |
    Then the results are not recorded

  Scenario: It cannot record two results for one racer
    Given the eventStep "Dutch Grand Prix 2022 Race" exists
    And the racer for "Verstappen At Red Bull Racing In 2022" exists
    When a client records the following results for eventStep "Dutch Grand Prix 2022 Race":
      | racer                                 | position | points |
      | Verstappen At Red Bull Racing In 2022 | 0        | 26     |
      | Verstappen At Red Bull Racing In 2022 | 4        | 26     |
    Then the results are not recorded
