#!/usr/bin/env python3
"""
Report Card OCR Validation Script
Uses Tesseract OCR to extract LRN, grades, and basic keywords from report card images.
"""

import sys
import json
import re
from pathlib import Path

try:
    import pytesseract
    from PIL import Image
except ImportError:
    print(json.dumps({
        "error": "Missing dependencies. Install: pip install pytesseract pillow",
        "lrn": None,
        "grades_found": 0,
        "word_count": 0,
        "flags": ["missing_dependencies"]
    }))
    sys.exit(1)

def extract_lrn(text):
    """Extract 12-digit LRN from text."""
    # Look for 12 consecutive digits
    lrn_pattern = r'\b\d{12}\b'
    matches = re.findall(lrn_pattern, text)
    if matches:
        return matches[0]
    return None

def count_grades(text):
    """Count numbers that look like grades (75-100)."""
    # Find all numbers
    numbers = re.findall(r'\b\d+\b', text)
    grade_count = 0
    for num_str in numbers:
        try:
            num = float(num_str)
            if 60 <= num <= 100:
                grade_count += 1
        except ValueError:
            continue
    return grade_count

def check_keywords(text):
    """Check for basic report card keywords."""
    keywords = ['quarter', 'grade', 'filipino', 'english', 'mathematics', 'science', 
                'araling panlipunan', 'mapeh', 'esp', 'tle', 'subject', 'average', 'makabayan',
                'computer', 'foreign language', 'music', 'arts', 'pe', 'health', 'periodic rating', 'Language',
                'literacy', 'makabansa', 'GMRC', 'Good Manners' ]
    text_lower = text.lower()
    found_keywords = [kw for kw in keywords if kw in text_lower]
    return len(found_keywords) > 0

def count_words(text):
    """Count words in text."""
    words = re.findall(r'\b\w+\b', text)
    return len(words)

def check_grade_level(text):
    """Check for grade level keywords."""
    grade_levels = ['grade 1', 'grade 2', 'grade 3', 'grade 4', 'grade 5', 'grade 6',
                    'g1', 'g2', 'g3', 'g4', 'g5', 'g6',
                    'Kindergarten 1', 'Kindergarten 2', 'K1', 'K2', 'Kindergarten-1', 'Kindergarten-2']
    text_lower = text.lower()
    found_levels = [gl for gl in grade_levels if gl in text_lower]
    return len(found_levels) > 0
def validate_report_card(image_path):
    """Main validation function - extracts data without per-image validation."""
    flags = []
    
    try:
        # Load and process image
        image = Image.open(image_path)
        
        # Perform OCR
        try:
            text = pytesseract.image_to_string(image, lang='eng')
        except Exception as e:
            flags.append("ocr_error")
            text = ""
        
        if not text or len(text.strip()) < 10:
            flags.append("no_text")
            return {
                "lrn": extract_lrn("") if text else None,
                "grades_found": 0,
                "word_count": 0,
                "flags": flags + ["low_text"]
            }
        
        # Extract information without validation
        lrn = extract_lrn(text)
        grades_found = count_grades(text)
        word_count = count_words(text)
        has_keywords = check_keywords(text)
        has_grade_level = check_grade_level(text)
        
        # Only flag obvious extraction issues, not content quality
        if not has_keywords:
            flags.append("no_keywords")
            
        if not has_grade_level:
            flags.append("no_grade_level")
        
        return {
            "lrn": lrn,
            "grades_found": grades_found,
            "word_count": word_count,
            "flags": flags
        }
    
    except FileNotFoundError:
        return {
            "lrn": None,
            "grades_found": 0,
            "word_count": 0,
            "flags": ["file_not_found"]
        }
    except Exception as e:
        return {
            "lrn": None,
            "grades_found": 0,
            "word_count": 0,
            "flags": ["processing_error"]
        }

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({
            "error": "Usage: python validate_card.py <image_path>",
            "lrn": None,
            "grades_found": 0,
            "word_count": 0,
            "flags": ["invalid_usage"]
        }))
        sys.exit(1)
    
    image_path = sys.argv[1]
    result = validate_report_card(image_path)
    print(json.dumps(result))
    sys.exit(0)

