-- Scoring System Database Schema
-- Create the database
CREATE DATABASE IF NOT EXISTS scoring_system;
USE scoring_system;

-- Users table (event participants)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    display_name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Judges table
CREATE TABLE judges (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    display_name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Scores table (allows multiple scores per user from different judges)
CREATE TABLE scores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    judge_id INT NOT NULL,
    points INT NOT NULL CHECK (points >= 0 AND points <= 100),
    comments TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (judge_id) REFERENCES judges(id) ON DELETE CASCADE,
    UNIQUE KEY unique_judge_user (judge_id, user_id)
);

-- Create indexes for better performance
CREATE INDEX idx_scores_user_id ON scores(user_id);
CREATE INDEX idx_scores_judge_id ON scores(judge_id);
CREATE INDEX idx_scores_points ON scores(points);

-- Insert some sample data for testing
INSERT INTO users (username, display_name, email) VALUES
('participant1', 'Alice Johnson', 'alice@example.com'),
('participant2', 'Bob Smith', 'bob@example.com'),
('participant3', 'Charlie Brown', 'charlie@example.com'),
('participant4', 'Diana Prince', 'diana@example.com'),
('participant5', 'Edward Norton', 'edward@example.com');

INSERT INTO judges (username, display_name, email) VALUES
('judge1', 'Dr. Sarah Wilson', 'sarah@example.com'),
('judge2', 'Prof. Michael Chen', 'michael@example.com'),
('judge3', 'Ms. Jennifer Davis', 'jennifer@example.com');

-- Insert some sample scores for demonstratio
INSERT INTO scores (user_id, judge_id, points, comments) VALUES
(1, 1, 85, 'Excellent presentation and technical skills'),
(1, 2, 78, 'Good work, minor improvements needed'),
(2, 1, 92, 'Outstanding performance'),
(2, 3, 88, 'Very impressive'),
(3, 1, 75, 'Solid effort'),
(3, 2, 82, 'Good technical execution'),
(4, 1, 90, 'Exceptional work'),
(5, 2, 79, 'Good overall performance');