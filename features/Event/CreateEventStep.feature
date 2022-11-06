Feature: It can create EventSteps

  @backoffice
  Scenario: It saves a new event step
    Given the stepType "race" exists
    And the event "Dutch Grand Prix 2022" exists
    When a client creates the "race" step for the event "Dutch Grand Prix 2022" at "2022-09-04 13:00:00"
    Then the event step is saved

  Scenario: It cannot save two event steps of the same type
    Given the eventStep "Dutch Grand Prix 2022 Race" exists
    When a client creates the "race" step for the event "Dutch Grand Prix 2022" at "2022-09-04 15:00:00"
    Then the event step creation for the same type is declined

  Scenario: It cannot save two event steps at the same time
    Given the stepType "qualification" exists
    And the eventStep "Dutch Grand Prix 2022 Race" exists
    When a client creates the "qualification" step for the event "Dutch Grand Prix 2022" at "2022-09-04 13:00:00"
    Then the event step creation for the same time is declined
