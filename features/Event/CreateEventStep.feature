Feature: It can create EventSteps

  Scenario: It saves a new event step
    Given a championship exists
    And a season exists for the championship
    And a country exists
    And a venue exists for the country
    And a step type exists
    And an event exists for the season and index
    When a client creates a new event step for the event and step type
    Then the event step is saved

  Scenario: It cannot save two event steps of the same type
    Given a championship exists
    And a season exists for the championship
    And a country exists
    And a venue exists for the country
    And a step type exists
    And an event exists for the season and index
    And an event step exists for the event and step type
    When a client creates a new event step for the same event and same step type and other time
    Then the event step creation for the same type is declined

  Scenario: It cannot save two event steps at the same time
    Given a championship exists
    And a season exists for the championship
    And a country exists
    And a venue exists for the country
    And a step type exists
    And an event exists for the season and index
    And an event step exists for the event and step type
    When a client creates a new event step for the same event and other step type and same time
    Then the event step creation for the same time is declined
