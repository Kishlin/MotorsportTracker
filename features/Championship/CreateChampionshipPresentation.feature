Feature: It can create Championship Presentations

  @backoffice
  Scenario: It saves a new championship presentation
    Given the championship "Formula One" exists
    When a client creates a championship presentation for "Formula One" with icon "f1.png" and color "#fff"
    Then the championship presentation is saved
    Then the latest championship presentation for "Formula One" has icon "f1.png" and color "#fff"

  @backoffice
  Scenario: It can overwrite championship presentations
    Given the championship "Formula One" exists
    And the championship presentation "Formula One White" exists
    When a client creates a championship presentation for "Formula One" with icon "f1.png" and color "#000"
    Then the championship presentation is saved
    And the latest championship presentation for "Formula One" has icon "f1.png" and color "#000"

  @backoffice @current
  Scenario: It updates the calendar views accordingly
    Given the championship presentation "Formula One White" exists
    And the calendar event view "Dutch Grand Prix 2022 Race White" exists
    When a client creates a championship presentation for "Formula One" with icon "f1.png" and color "#ddd"
    Then the championship presentation is saved
    And the event step calendar for "formula1" "Dutch GP" "race" has the "#ddd" color
    When a client creates a championship presentation for "Formula One" with icon "f1.png" and color "#abc"
    Then the championship presentation is saved
    And the event step calendar for "formula1" "Dutch GP" "race" has the "#abc" color
