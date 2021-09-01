<?php

    $buyer = new stdClass();
    $buyer->wallet = [
        1 => 10,
        2 => 10,
        5 => 10,
        10 => 10,
        20 => 10,
        50 => 6,
        100 => 5,
        200 => 0
    ];

    $coffee = new stdClass();
    $coffee->name = "Nescafe";
    $coffee->price = 70;
    $coffee->qty = 5;

    $coffee1 = new stdClass();
    $coffee1->name = "Latte";
    $coffee1->price = 50;
    $coffee1->qty = 6;

    $coffee2 = new stdClass();
    $coffee2->name = "Americano";
    $coffee2->price = 90;
    $coffee2->qty = 3;

    $items = [$coffee, $coffee1, $coffee2];

    function calculateTotalMoney(object $person): int
    {
        $totalMoney = 0;
        foreach($person->wallet as $coin => $quantity){
            $totalMoney += $coin * $quantity;
        }
        return $totalMoney;
    }

    $total = calculateTotalMoney($buyer);

    while (true) {

        foreach ($items as $index => $item) {
            echo "$index | $item->name {$item->price}¢ [$item->qty]\n";
        }
        echo "Total money: {$total}¢\n";

        while (true) {
            $selection = readline("Choose the product: ");

            if (!isset($items[$selection]) || !is_numeric($selection)) {
                echo "Invalid Input!\n";
                continue;
            }
            break;
        }

        echo "{$items[$selection]->name} {$items[$selection]->price}¢ [{$items[$selection]->qty}]\n";
        echo "Total money: {$total}¢\n";

        $prompt = true;
        while ($prompt) {
            $buy = readline("Do you want to buy it? (Y/N) ");

            switch (strtoupper($buy)) {
                case "Y" || "N":
                    $prompt = false;
                    break;
                default:
                    echo "Wrong input. Try again!\n";
                    break;
            }
        }

        if (strtoupper($buy) === "N") continue;

        $coinsInserted = 0;
        while ($items[$selection]->price > $coinsInserted) {
            $priceLeft = $items[$selection]->price - $coinsInserted;
            echo "{$items[$selection]->name} Left to pay: {$priceLeft}¢\n";
            $coin = (int) readline("Insert coin: ");
            if(!isset($buyer->wallet[$coin])){
                echo "Invalid coin! Try again!\n";
                continue;
            }

            if($buyer->wallet[$coin] <= 0){
                echo "Not enough coins left! Try again!\n";
                continue;
            }

            $coinsInserted += $coin;
            $buyer->wallet[$coin]--;

        }

        if($coinsInserted > $items[$selection]->price){
            $change = $coinsInserted - $items[$selection]->price;

            foreach(array_reverse(array_keys($buyer->wallet)) as $coin){
                $quantity = intdiv($change, $coin);
                $buyer->wallet[$coin] += $quantity;
                $change -= $coin * $quantity;
            }

        }
        $total -= $items[$selection]->price;
        break;

    }

    echo "Bye!\nYour balance: {$total}¢";