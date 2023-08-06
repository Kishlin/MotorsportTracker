Feature: It can view the calendar

  Scenario: It views an empty calendar
    Given there are no events planned
    When a client views the calendar from "2022-09-01" to "2022-09-30"
    Then an empty calendar is viewed

  Scenario: It views a calendar of one event
    Given the calendar event "Formula One 2022 Dutch GP" exists
    When a client views the calendar from "2022-01-01" to "2022-12-31"
    Then a calendar is viewed with events
      | key        | count | slug                   |
      | 2022-11-22 | 1     | formula-one_0_dutch-gp |

  Scenario: It views a complex calendar
    Given the calendar event "Formula One 2022 Dutch GP" exists
    And the calendar event "Formula One 2022 Emilia Romagna GP" exists
    When a client views the calendar from "2022-01-01" to "2022-12-31"
    Then a calendar is viewed with events
      | key        | count | slug                   |
      | 2022-11-22 | 1     | formula-one_0_dutch-gp |
      | 2022-04-23 | 1     | Emilia Romagna-gp      |

  Scenario: It views a calendar with dates filtered
    Given the calendar event "Formula One 2022 Dutch GP" exists
    And the calendar event "Formula One 2022 Emilia Romagna GP" exists
    When a client views the calendar from "2022-04-01" to "2022-04-30"
    Then a calendar is viewed with events
      | key        | count | slug                   |
      | 2022-04-23 | 1     | Emilia Romagna-gp      |
