<?php
$cost = 0;
$cart = [];
$file = file_get_contents('products.json');
$data = json_decode($file, true);
foreach ($data['catalogue'] as &$item) {
    $item['name'] = strtolower($item['name']);
}
while (true) {
    echo "_____________________________________" . PHP_EOL;
    echo "1 - Display products 
2 - Add to cart
3 - Remove from cart
4 - Check cart
5 - Purchase cart \n";
    echo "Enter choice 1-5: " . PHP_EOL;
    $choice = (int)readline();
    echo "_____________________________________" . PHP_EOL;
    switch ($choice) {
        case 1:
            echo "Products: " . PHP_EOL;
            foreach ($data["catalogue"] as $i => $product) {
                echo "[$i] " . $product["name"] . " cost: $" .
                    number_format($product["price"] / 100, 2) . "\n";
            }
            break;
        case 2:
            echo "Enter item name or item index: " . PHP_EOL;
            $addItem = strtolower(readline());
            $cartItem = null;
            $cartIndex = null;

            foreach ($data["catalogue"] as $index => $checkItem) {
                if ($addItem === $checkItem["name"] || $addItem == $index) {
                    $cartItem = $checkItem["name"];
                    $cartIndex = $index;
                    break;
                }
            }
            if ($cartItem !== null) {
                echo "Enter amount of items: " . PHP_EOL;
                $amount = (int)readline();
                if ($amount < 1) {
                    echo "Wrong input." . PHP_EOL;
                } else {
                    $add = new stdClass();
                    $add->name = $cartItem;
                    $add->amount = $amount;
                    $add->cost = $data["catalogue"][$cartIndex]["price"] / 100;
                    $add->price = $data["catalogue"][$cartIndex]["price"] * $amount / 100;
                    $isInCart = false;
                    foreach ($cart as $inCart) {
                        if ($inCart->name === $add->name) {
                            $inCart->amount += $add->amount;
                            $inCart->price += $add->price;
                            $isInCart = true;
                            break;
                        }
                    }
                    if ($isInCart === false) {
                        $cart[] = $add;
                    }
                }
            } else {
                echo "Item not found." . PHP_EOL;
            }
            break;
        case 3:
            if (sizeof($cart) > 0) {
                echo "Enter item name or item index to remove from cart: " . PHP_EOL;
                $removeItem = strtolower(readline());
                $cartItem = null;
                $cartIndex = null;

                foreach ($cart as $index => $checkItem) {
                    if ($removeItem === $checkItem->name || $removeItem == $index) {
                        $cartItem = $checkItem->name;
                        $cartIndex = $index;
                        break;
                    }
                }
                if ($cartItem !== null) {
                    echo "Enter amount of items to remove: " . PHP_EOL;
                    $amount = (int)readline();
                    if ($amount < 1) {
                        echo "Wrong input." . PHP_EOL;
                    } else {
                        $remove = new stdClass();
                        $remove->name = $cartItem;
                        $remove->amount = $amount;
                        $remove->price = $cart[$cartIndex]->price;
                        foreach ($cart as $index => $inCart) {
                            if ($inCart->name === $remove->name) {
                                $inCart->amount -= $remove->amount;
                                $inCart->price -= $inCart->cost * $remove->amount;
                                if ($inCart->amount <= 0) {
                                    unset($cart[$cartIndex]);
                                    $cart = array_values($cart);
                                }
                                break;
                            }
                        }
                    }
                } else {
                    echo "Item not found." . PHP_EOL;
                }
            } else {
                echo "Cart empty." . PHP_EOL;
            }
            break;
        case 4:
            echo "Cart: " . PHP_EOL;
            if (sizeof($cart) > 0) {
                foreach ($cart as $i => $cartItem) {
                    echo "[$i] " . $cartItem->name . "\n" .
                        "amount: " . $cartItem->amount . " price: $" .
                        number_format($cartItem->price, 2) . PHP_EOL;
                    $cost += $cartItem->price;
                    echo "_____________________________________" . PHP_EOL;
                }
                echo "Your total is $" . number_format($cost, 2) . PHP_EOL;
                $cost = 0;
            } else {
                echo "Cart is empty." . PHP_EOL;
            }
            break;
        case 5:
            if (sizeof($cart) > 0) {
                foreach ($cart as $i => $cartItem) {
                    echo "You've bought " . $cartItem->name . "\n" .
                        "amount: " . $cartItem->amount . " price: $" . number_format($cartItem->price, 2) . PHP_EOL;
                    $cost += $cartItem->price;
                    echo "_____________________________________" . PHP_EOL;
                }
                echo "Your total is $" . number_format($cost, 2) . PHP_EOL;
            } else {
                echo "Cart is empty." . PHP_EOL;
            }
            echo "_____________________________________" . PHP_EOL;
            exit;
        default:
            echo "incorrect choice" . PHP_EOL;
            break;
    }
}