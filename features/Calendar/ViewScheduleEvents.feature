Feature: It can view a schedule

  @api
  Scenario: It views an empty schedule
    Given there are no events planned
    When a client views the schedule for "Formula One" "2022"
    Then an empty schedule is viewed

  @api
  Scenario: It views a schedule of one event
    Given the calendar event "Moto GP 1949 Tourist Trophy" exists
    When a client views the schedule for "MotoGP" "1949"
    Then a schedule is viewed with events
      | index | slug                       |
      | 0     | motogp_1949_tourist-trophy |

  @api
  Scenario: It views a complex schedule
    Given the calendar event "Moto GP 1949 Tourist Trophy" exists
    And the calendar event "Moto GP 1949 Switzerland Grand Prix" exists
    When a client views the schedule for "MotoGP" "1949"
    Then a schedule is viewed with events
      | index | slug                               |
      | 0     | motogp_1949_tourist-trophy         |
      | 1     | motogp_1949_switzerland-grand-prix |

  @api
  Scenario: It views a schedule for a specific championship
    Given the calendar event "Moto GP 1949 Tourist Trophy" exists
    And the calendar event "Moto GP 1949 Switzerland Grand Prix" exists
    And the calendar event "Formula One 1950 Swiss Grand Prix" exists
    When a client views the schedule for "Formula One" "1950"
    Then a schedule is viewed with events
      | index | slug                              |
      | 0     | formula-one_1950_swiss-grand-prix |
