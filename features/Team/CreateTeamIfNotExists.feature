Feature: It can create Teams

  Scenario: It saves a new team
    Given the country "Austria" exists
    When a client creates the team "Red Bull Racing" for the country "Austria"
    Then the team is saved
    And the id of the team "Red Bull Racing" is returned

  Scenario: It returns the id of an existing team
    Given the "Red Bull Racing" team exists
    When a client creates a team with the same name
    Then the team is not duplicated
    And the id of the team "Red Bull Racing" is returned
