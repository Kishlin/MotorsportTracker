Feature: It views cached events

  @api
  Scenario: It views an empty list when there are none
    Given there are no events cached
    When a client views cached events
    Then it views an empty list of events

  @api
  Scenario: It views a list of some cached events
    Given the event cached "MotoGP Americas Grand Prix" exists
    And the event cached "Australian Grand Prix" exists
    And the event cached "Hungarian Grand Prix" exists
    When a client views cached events
    Then it views a list of "3" events
    And there is a view for event "Formula One" "2022" "Australian Grand Prix"
    And there is a view for event "Formula One" "2019" "Hungarian Grand Prix"
    And there is a view for event "Motogp" "2022" "Americas Grand Prix"
