Feature: It can search a team

  Scenario: It searches an existing team
    Given the "Red Bull Racing" team exists
    When a client searches a team with keyword "Red Bull"
    Then the id of the team "Red Bull Racing" is returned

  Scenario: It searches a team that does not exist
    Given the "team" "Red Bull racing" does not exist yet
    When a client searches a team with keyword "Red"
    Then it does not receive any team id
