Feature: It can create Team Presentations

  Scenario: It saves a new team presentation
    Given the country "Austria" exists
    And the "Red Bull Racing" team exists
    And the season "Formula One 2022" exists
    When a client creates a presentation with name "Red Bull Racing" and color "#0000c6"
    Then the team presentation is saved
    And the id of the team presentation "Red Bull Racing" is returned

  Scenario: It returns the id of an existing team presentation
    Given the team presentation "Red Bull Racing 2022" exists
    When a client creates a presentation for the same team and season
    Then the team presentation is not duplicated
    And the id of the team presentation "Red Bull Racing" is returned
