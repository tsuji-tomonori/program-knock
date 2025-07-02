package com.programknock;

import java.util.*;

public class SushiSeating {

    public static List<String> sushiSeating(List<String> commands) {
        Queue<String> waitingQueue = new LinkedList<>(); // FIFO queue for waiting customers
        List<String> seatedCustomers = new ArrayList<>(); // List of seated customers in order

        for (String command : commands) {
            String[] parts = command.split(" ", 2);

            if (parts[0].equals("arrive")) {
                String name = parts[1];
                // Add to queue only if not already in queue
                if (!waitingQueue.contains(name)) {
                    waitingQueue.add(name);
                }
            } else if (parts[0].equals("seat")) {
                int n = Integer.parseInt(parts[1]);
                // Seat up to n customers from the front of the queue
                int seatsToFill = Math.min(n, waitingQueue.size());
                for (int i = 0; i < seatsToFill; i++) {
                    String customer = waitingQueue.poll();
                    if (customer != null) {
                        seatedCustomers.add(customer);
                    }
                }
            }
        }

        return seatedCustomers;
    }
}
