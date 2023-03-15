Feature: It can create Teams

  Scenario: It saves a new team
    When a client creates a team with the ref "41be2072-17ab-455f-8522-8b96bc315e47"
    Then the team is saved
    And the id of the team with ref "41be2072-17ab-455f-8522-8b96bc315e47" is returned

  Scenario: It returns the id of an existing team
    Given the "Red Bull Racing" team exists
    When a client creates a team with the same ref
    Then the team is not duplicated
    And the id of the team with ref "41be2072-17ab-455f-8522-8b96bc315e47" is returned
