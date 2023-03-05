Feature: It can create Teams

  Scenario: It saves a new team
    Given the country "Austria" exists
    When a client creates the team "Red Bull Racing" for the country "Austria"
    Then the team is saved

  Scenario: It cannot duplicate team
    Given the "Red Bull Racing" team exists
    When a client creates a team with the same name
    Then the team creation is declined
