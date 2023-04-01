Feature: It views season events

  @api
  Scenario: It views an existing season events list
    Given the season events for "First Three Events Of Formula One 2022" exist
    When a client views the season events for "Formula One" "2022"
    Then it views season events for "Formula One" "2022"
    And it views a season event of slug "bahrain-grand-prix"
    And it views a season event of slug "saudi-arabian-grand-prix"
    And it views a season event of slug "australian-grand-prix"

  @api
  Scenario: It tries to view a missing season events list
    Given the seasonEvents "for Formula One 2022" does not exist yet
    When a client views the season events for "Formula One" "2022"
    Then it gets a missing season events error
