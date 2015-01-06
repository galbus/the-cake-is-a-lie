# The Cake is a Lie - JSON RESFful API

## Note

*This project is a CodeIgniter demonstration for a job application and, as such, it is incomplete. The primary purpose is to demonstrate technical understanding, coding standards and documentation standards, not to function as working software.*

## Overview

This API enables you to take part in a cake voting content. Mmmmmmm.

GET Methods can be called through a standard web browser by visiting the URL.

PUT methods require data sent to them and should be called using e.g. CURL.

## Available API Methods

### List Recipies

    GET /api/winner

Lists all recipies in the system.

#### Parameters

None

### Winning Recipe

    GET /api/winner

Returns the winning Recipe. The winning recipe is determined by the number of votes each recipe receives. If two or more recipies receive the same amount of votes then the recipe that was entered first is the winner.

#### Parameters

### Add Recipe

    PUT /api/winner

Adds a recipe to the system.

#### Parameters

* **name**: (String) 100 chars or less
* **description**: (String) 250 chars or less
* **ingredients**: (String) 10,000 chars or less
* **method**: (String) 10,000 chars or less
* **cooking_time**: (Time, Optional) HH:MM:SS
* **prep_time**: (Time, Optional) HH:MM:SS
* **yield**: (String, Optional) 100 chars or less
* **author**: (Integer) A valid user ID

#### Returns

##### Successful Response

    {
        "status": "success",
        "name": "Name",
        "description": "Description",
        "ingredients": "Ingredients",
        "method": "Method",
        "cooking_time": "00:30:00",
        "prep_time": "00:40:00",
        "yield": "Yield",
        "author": "1"
    }

##### Error Response

    {
        "status": "failed",
    }

### Register Vote

    PUT /api/winner

Registers a vote for a recipe. Although users users can vote for as many different recipies as they cannot vote for the same recipe more than once.

#### Parameters

* **recipe**: (Integer) A valid recipe ID
* **author**: (Integer) A valid user ID

#### Returns

To follow...
