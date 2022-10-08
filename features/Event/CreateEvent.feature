Feature: It can create Events

  Scenario: It saves a new event
    Given a championship exists
    And a season exists for the championship
    And a country exists
    And a venue exists for the country
    When a client creates a new event for the season venue index and label
    Then the event is saved

  Scenario: It cannot create two events with the same index in a championship
    Given a championship exists
    And a season exists for the championship
    And a country exists
    And a venue exists for the country
    And an event exists for the season and index
    When a client creates an event for the same season and index
    Then the event creation with the same index is declined

  Scenario: It cannot create two events with the same label in a championship
    Given a championship exists
    And a season exists for the championship
    And a country exists
    And a venue exists for the country
    And an event exists for the season and label
    When a client creates an event for the same season and label
    Then the event creation with the same label is declined
